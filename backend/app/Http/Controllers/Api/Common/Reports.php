<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Report as Request;
use App\Http\Resources\Common\Report as Resource;
use App\Jobs\Common\CreateReport;
use App\Jobs\Common\DeleteReport;
use App\Jobs\Common\UpdateReport;
use App\Models\Common\Report;
class Reports extends ApiController
{
    public function index()
    {
        $reports = Report::collect();
        return Resource::collection($reports);
    }
    public function show(Report $report)
    {
        return new Resource($report);
    }
    public function store(Request $request)
    {
        $report = $this->dispatch(new CreateReport($request));
        return $this->created(route('api.reports.show', $report->id), new Resource($report));
    }
    public function update(Report $report, Request $request)
    {
        $report = $this->dispatch(new UpdateReport($report, $request));
        return new Resource($report->fresh());
    }
    public function destroy(Report $report)
    {
        try {
            $this->dispatch(new DeleteReport($report));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
