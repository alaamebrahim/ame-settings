<?php

namespace Alaame\Setting\Repositories;

use Alaame\Setting\Models\Setting;

class SettingRepository extends BaseRepository
{
    protected $fieldSearchable = [

    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Setting::class;
    }

    public function ordered() {
        return $this->model->orderBy('order', 'ASC')->get();
    }

    public function getDistinctGroups(){
        return $this->model->select('group')->distinct()->get();
    }

    public function getCountOfKey($key) {
        return $this->model->where('key', $key)->count() ?? 0;
    }

    public function getLastSetting(){
        return $this->model->orderBy('order', 'DESC')->first();
    }

    public function getPreviousSetting($swapOrder, $group)
    {
        $previousSetting = $this->model->where('order', '<', $swapOrder)
            ->where('group', $group)
            ->orderBy('order', 'DESC')->first();
        return $previousSetting;
    }

    public function getNextSetting($swapOrder, $group)
    {
        $nextSetting = $this->model->where('order', '>', $swapOrder)
            ->where('group', $group)
            ->orderBy('order', 'ASC')->first();
        return $nextSetting;
    }
}
