<?php
namespace App\Exceptions;
use Akaunting\Money\Exceptions\UnexpectedAmountException;
use App\Events\Email\InvalidEmailDetected;
use App\Exceptions\Http\Resource as ResourceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\ViewException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\Exception\HttpTransportException as MailerHttpTransportException;
use Throwable;
class Handler extends ExceptionHandler
{
    protected $levels = [
    ];
    protected $dontReport = [
    ];
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (config('logging.default') == 'bugsnag') {
                call_user_func(config('bugsnag.before_send'), $e);
            }
        });
    }
    public function report(Throwable $exception)
    {
        if ($exception instanceof MailerHttpTransportException) {
            $email = $this->handleMailerExceptions($exception);
            if (! empty($email)) {
                return;
            }
        }
        parent::report($exception);
    }
    public function render($request, Throwable $exception)
    {
        if (request_is_api($request)) {
            return $this->handleApiExceptions($request, $exception);
        }
        if (config('app.debug') === false) {
            return $this->handleWebExceptions($request, $exception);
        }
        return parent::render($request, $exception);
    }
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->fullUrl() !== config('app.url')) {
            session(['url.intended' => $request->fullUrl()]);
        }
        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->to($exception->redirectTo() ?? route('login'));
    }
    protected function handleWebExceptions($request, $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => trans('errors.header.404'),
                ], 404);
            }
            flash(trans('errors.body.page_not_found'))->error()->important();
            return redirect()
                ->back()
                ->withErrors(['msg', trans('errors.body.page_not_found')]);
        }
        if ($exception instanceof ModelNotFoundException) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => trans('errors.header.404'),
                ], 404);
            }
            try {
                $names = explode('.', $request->route()->getName());
                $names[count($names) - 1] = 'index';
                $route = route(implode('.', $names));
                flash(trans('errors.message.record'))->warning()->important();
                return redirect($route);
            } catch (\Exception $e) {
                return response()->view('errors.404', [], 404);
            }
        }
        if ($exception instanceof FatalError) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => trans('errors.header.500'),
                ], 500);
            }
            return response()->view('errors.500', [], 500);
        }
        if ($exception instanceof ThrottleRequestsException) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => $exception->getMessage(),
                ], 429);
            }
        }
        if ($exception instanceof ViewException) {
            $real_exception = $this->getRealException($exception, ViewException::class);
            if ($real_exception instanceof UnexpectedAmountException) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => trans('errors.message.amount'),
                    ], 500);
                }
                return response()->view('errors.500', [
                    'message' => trans('errors.message.amount'),
                ], 500);
            }
        }
        if ($exception instanceof MailerHttpTransportException) {
            $email = $this->handleMailerExceptions($exception);
            if (! empty($email)) {
                $message = trans('notifications.menu.invalid_email.description', ['email' => $email]);
                if ($request->ajax()) {
                    return response()->json([
                        'error' => $message,
                    ], $exception->getCode());
                }
                return response()->view('errors.403', [
                    'message' => $message,
                ], $exception->getCode());
            }
        }
        return parent::render($request, $exception);
    }
    protected function handleApiExceptions($request, $exception): Response
    {
        $replacements = $this->prepareApiReplacements($exception);
        $response = config('api.error_format');
        array_walk_recursive($response, function (&$value, $key) use ($replacements) {
            if (Str::startsWith($value, ':') && isset($replacements[$value])) {
                $value = $replacements[$value];
            }
        });
        $response = $this->recursivelyRemoveEmptyApiReplacements($response);
        return new Response($response, $this->getStatusCode($exception), $this->getHeaders($exception));
    }
    protected function handleMailerExceptions(MailerHttpTransportException $exception): string
    {
        preg_match("/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/", $exception->getMessage(), $matches);
        if (empty($matches[0])) {
            return '';
        }
        $email = $matches[0];
        event(new InvalidEmailDetected($email, $exception->getMessage()));
        return $email;
    }
    protected function prepareApiReplacements(Throwable $exception): array
    {
        $code = $this->getStatusCode($exception);
        if (! $message = $exception->getMessage()) {
            $message = sprintf('%d %s', $code, Response::$statusTexts[$code]);
        }
        $replacements = [
            ':message' => $message,
            ':status_code' => $code,
        ];
        if ($exception instanceof ResourceException && $exception->hasErrors()) {
            $replacements[':errors'] = $exception->getErrors();
        }
        if ($exception instanceof ValidationException) {
            $replacements[':errors'] = $exception->errors();
            $replacements[':status_code'] = $exception->status;
        }
        if ($code = $exception->getCode()) {
            $replacements[':code'] = $code;
        }
        if (config('api.debug')) {
            $replacements[':debug'] = [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
            if (! is_null($exception->getPrevious())) {
                $currentTrace = $replacements[':debug']['trace'];
                $replacements[':debug']['trace'] = [
                    'previous' => explode("\n", $exception->getPrevious()->getTraceAsString()),
                    'current' => $currentTrace,
                ];
            }
        }
        return $replacements;
    }
    protected function recursivelyRemoveEmptyApiReplacements(array $input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->recursivelyRemoveEmptyApiReplacements($value);
            }
        }
        return array_filter($input, function ($value) {
            if (is_string($value)) {
                return ! Str::startsWith($value, ':');
            }
            return true;
        });
    }
    protected function getStatusCode(Throwable $exception): int
    {
        $code = null;
        if ($exception instanceof ValidationException) {
            $code = $exception->status;
        } elseif ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        } else {
            $code = 500;
        }
        if ($code < 100 || $code > 599) {
            $code = 500;
        }
        return $code;
    }
    protected function getHeaders(Throwable $exception): array
    {
        return ($exception instanceof HttpExceptionInterface)
                ? $exception->getHeaders()
                : [];
    }
    protected function getRealException(Throwable $exception, string $current): Throwable
    {
        $previous = $exception->getPrevious() ?? $exception;
        while (($previous instanceof $current) && $previous->getPrevious()) {
            $previous = $previous->getPrevious();
        }
        return $previous;
    }
}
