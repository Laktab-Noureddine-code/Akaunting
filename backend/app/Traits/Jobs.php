<?php
namespace App\Traits;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Throwable;
trait Jobs
{
    public function dispatch($job)
    {
        $function = $this->getDispatchFunction();
        return $this->$function($job);
    }
    public function dispatchQueue($job)
    {
        return app(Dispatcher::class)->dispatch($job);
    }
    public function dispatchSync($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchSync($job, $handler);
    }
    public function ajaxDispatch($job)
    {
        try {
            $data = $this->dispatch($job);
            $response = [
                'success' => true,
                'error' => false,
                'data' => $data,
                'message' => '',
            ];
        } catch (Exception | Throwable $e) {
            $response = [
                'success' => false,
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }
    public function getDispatchFunction()
    {
        return should_queue() ? 'dispatchQueue' : 'dispatchSync';
    }
}
