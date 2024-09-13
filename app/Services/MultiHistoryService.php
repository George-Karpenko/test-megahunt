<?php

namespace App\Services;

use App\Enums\History\ActionEnum;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MultiHistoryService
{
    private array $histories = [];
    private $selectStatement;

    public function __construct(array $ids, $model)
    {
        $this->selectStatement = $model::withTrashed()->whereIn('id', $ids);
        $models = $this->selectStatement->get();
        foreach ($models as $model) {
            $history = [];
            $history['id'] = Str::uuid();
            $history['model_id'] = $model->id;
            $history['model_name'] = get_class($model);
            $history['after'] = $model->toJson();
            $history['before'] = $model->toJson();
            $this->histories[] = $history;
        }
    }

    public function multiRestore()
    {
        $this->dbAction(ActionEnum::Restore, 'restore');
    }

    public function multiDestroy()
    {
        $this->dbAction(ActionEnum::Delete, 'delete');
    }

    public function multiHardDestroy()
    {
        $this->dbAction(ActionEnum::HardDelete, 'forceDelete');
    }

    private function dbAction($actionEnum, $cd)
    {
        for ($i = 0; $i < count($this->histories); $i++) {
            $this->histories[$i]['action'] = $actionEnum;
        }
        DB::transaction(function () use ($cd) {
            $this->selectStatement->$cd();
            History::insert($this->histories);
        });
    }
}
