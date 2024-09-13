<?php

namespace App\Services;

use App\Enums\History\ActionEnum;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MultiHistoryService
{
    private array $histories = [];
    private $nModels;

    public function __construct(array $ids, $model)
    {
        $this->nModels = $model::withTrashed()->whereIn('id', $ids);
        $models = $this->nModels->get();
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
        $this->multiTach(ActionEnum::Restore, 'restore');
    }

    public function multiDestroy()
    {
        $this->multiTach(ActionEnum::Delete, 'delete');
    }

    public function multiHardDestroy()
    {
        $this->multiTach(ActionEnum::HardDelete, 'forceDelete');
    }

    private function multiTach($actionEnum, $cd)
    {
        for ($i = 0; $i < count($this->histories); $i++) {
            $this->histories[$i]['action'] = $actionEnum;
        }
        DB::transaction(function () use ($cd) {
            $this->nModels->$cd();
            History::insert($this->histories);
        });
    }
}
