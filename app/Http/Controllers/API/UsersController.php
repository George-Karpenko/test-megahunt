<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MultiUsersRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\History;
use App\Models\User;
use App\Services\HistoryService;
use App\Services\MultiHistoryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query();
        $users = User::when(
            array_key_exists('column_where', $query) && array_key_exists('where_value', $query),
            fn($q) => $q->where($query['column_where'], 'LIKE', '%' . $query['where_value'] . '%')
        )
            ->when(
                array_key_exists('column_order', $query) && array_key_exists('order_by', $query),
                fn($q) => $q->orderBy($query['column_order'], $query['order_by'])
            )
            ->paginate();
        return new UserCollection($users);
    }

    public function indexOnlyTrashed(Request $request)
    {
        $query = $request->query();
        $users = User::when(
            array_key_exists('column_where', $query) && array_key_exists('where_value', $query),
            fn($q) => $q->where($query['column_where'], 'LIKE', '%' . $query['where_value'] . '%')
        )
            ->when(
                array_key_exists('column_order', $query) && array_key_exists('order_by', $query),
                fn($q) => $q->orderBy($query['column_order'], $query['order_by'])
            )
            ->paginate();
        return new UserCollection($users);
    }

    public function show($id)
    {
        $user = Cache::remember('user', Carbon::now()->addMinutes(30), function () use ($id) {
            return User::find($id);
        });
        $historyService = new HistoryService($user);
        return new UserResource($historyService->show());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $historyService = new HistoryService($user);
        return new UserResource($historyService->update($request->all()));
    }

    public function destroy(User $user)
    {
        $historyService = new HistoryService($user);
        $historyService->destroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function hardDestroy($id)
    {
        $user = User::withTrashed()->find($id);
        $historyService = new HistoryService($user);
        $historyService->hardDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiRestore(MultiUsersRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), User::class);
        $historyService->multiRestore();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiDestroy(MultiUsersRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), User::class);
        $historyService->multiDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function multiHardDestroy(MultiUsersRequest $request)
    {
        $historyService = new MultiHistoryService($request->input('ids'), User::class);
        $historyService->multiHardDestroy();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function restorationFromHistories(History $history)
    {
        HistoryService::restorationFromHistories($history);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
