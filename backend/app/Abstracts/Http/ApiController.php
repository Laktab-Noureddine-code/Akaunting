<?php
namespace App\Abstracts\Http;
use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Relationships;
use App\Exceptions\Http\Resource as ResourceException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;
abstract class ApiController extends BaseController
{
    use AuthorizesRequests, Jobs, Permissions, Relationships, ValidatesRequests;
    public function __construct()
    {
        $this->assignPermissionsToController();
    }
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            throw new ResourceException('Validation Error', $errors);
        }
        return redirect()->to($this->getRedirectUrl())->withInput($request->input())->withErrors($errors, $this->errorBag());
    }
    public function created($location, $resource): JsonResponse
    {
        return $resource
                ->response()
                ->setStatusCode(201)
                ->header('Location', $location);
    }
    public function accepted($location, $resource): JsonResponse
    {
        return $resource
                ->response()
                ->setStatusCode(202)
                ->header('Location', $location);
    }
    public function noContent(): Response
    {
        return (new Response)
                ->setStatusCode(204);
    }
    public function error($message, $statusCode)
    {
        throw new HttpException($statusCode, $message);
    }
    public function errorNotFound($message = 'Not Found')
    {
        $this->error($message, 404);
    }
    public function errorBadRequest($message = 'Bad Request')
    {
        $this->error($message, 400);
    }
    public function errorForbidden($message = 'Forbidden')
    {
        $this->error($message, 403);
    }
    public function errorInternal($message = 'Internal Error')
    {
        $this->error($message, 500);
    }
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->error($message, 401);
    }
    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->error($message, 405);
    }
}
