<?php

namespace App\Services;

use App\Enums\History\ActionEnum;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoryService
{
    private History $history;
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->history = new History;
        $this->history->model_id = $model->id;
        $this->history->model_name = get_class($model);
        $this->history->after = $model->toJson();
    }

    public function show()
    {
        return $this->dbAction(ActionEnum::View);
    }

    public function update($requestAll)
    {
        return $this->dbAction(ActionEnum::Edit, 'update', $requestAll);
    }

    public function restore()
    {
        $this->dbAction(ActionEnum::Restore, 'restore');
    }

    public function destroy()
    {
        $this->dbAction(ActionEnum::Delete, 'delete');
    }

    public function hardDestroy()
    {
        $this->dbAction(ActionEnum::HardDelete, 'forceDelete');
    }

    private function dbAction($actionEnum, $cb = null, $requestAll = null)
    {
        $this->history->action = $actionEnum;
        DB::transaction(function () use ($cb, $requestAll) {
            if ($cb) {
                $this->model->$cb($requestAll);
            }
            $this->history->before = $this->model->toJson();
            $this->history->save();
        });

        return $this->model;
    }

    public static function restorationFromHistories(History $history)
    {
        $history->model_name::create(json_decode($history->after, true));
    }
}
