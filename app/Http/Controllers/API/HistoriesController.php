<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MultiHistoriesRequest;
use App\Http\Resources\HistoryCollection;
use App\Http\Resources\HistoryResource;
use App\Models\History;
use App\Services\HistoryService;
use App\Services\MultiHistoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $histories = History::when(
            array_key_exists('column_where', $query) && array_key_exists('where_value', $query),
            fn($q) => $q->where($query['column_where'], 'LIKE', '%' . $query['where_value'] . '%')
        )
            ->when(
                array_key_exists('column_order', $query) && array_key_exists('order_by', $query),
                fn($q) => $q->orderBy($query['column_order'], $query['order_by'])
            )
            ->paginate();
        return new HistoryCollection($histories);
    }

    public function indexOnlyTrashed(Request $request)
    {
        $query = $request->query();
        $histories = History::when(
            array_key_exists('column_where', $query) && array_key_exists('where_value', $query),
            fn($q) => $q->where($query['column_where'], 'LIKE', '%' . $query['where_value'] . '%')
        )
            ->when(
                array_key_exists('column_order', $query) && array_key_exists('order_by', $query),
                fn($q) => $q->orderBy($query['column_order'], $query['order_by'])
            )
            ->paginate();
        return new HistoryCollection($histories);
    }

    public function show($id)
    {
        $history = Cache::remember('history', Carbon::now()->addMinutes(30), function () use ($id) {
            return History::find($id);
        });
        $historyService = new HistoryService($history);
        return new HistoryResource($historyService->show());
    }

    public function destroy(History $history)
    {
        $historyService = new HistoryService($history);
        $historyService->destroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function hardDestroy($id)
    {
        $history = History::withTrashed()->find($id);
        $historyService = new HistoryService($history);
        $historyService->hardDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiRestore(MultiHistoriesRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), History::class);
        $historyService->multiRestore();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiDestroy(MultiHistoriesRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), History::class);
        $historyService->multiDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiHardDestroy(MultiHistoriesRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), History::class);
        $historyService->multiHardDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function restorationFromHistories(History $history)
    {
        HistoryService::restorationFromHistories($history);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
