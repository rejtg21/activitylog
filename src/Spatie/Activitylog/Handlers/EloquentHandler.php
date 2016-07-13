<?php

namespace Spatie\Activitylog\Handlers;

use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class EloquentHandler implements ActivitylogHandlerInterface
{
    protected $model;

    public function __construct()
    {
        // identify what model to be used
        $this->model = config('activitylog.modelPath') ?: 'Activity';
    }

    /**
     * Log activity in an Eloquent model.
     *
     * @param string $text
     * @param $userId
     * @param array  $attributes
     *
     * @return bool
     */
    public function log($data)
    {
        $model = $this->model;
        return \DB::transaction(function() use ($data, $model){

            $activity = $model::create([
                'user_id' => $data->user_id,
                'text' => $data->text,
                'ip_address' => $data->ip_address
            ]);
            // add the activity to data
            $data->activity = $activity;
            return $this->_shouldLogAfter($data);
        });
    }

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return bool
     */
    public function cleanLog($maxAgeInMonths)
    {
        $model = $this->model;

        $minimumDate = Carbon::now()->subMonths($maxAgeInMonths);
        $model::where('created_at', '<=', $minimumDate)->delete();

        return true;
    }

    private function _shouldLogAfter($data)
    {
        $afterHandler = config('activitylog.afterHandler');
        if(EMPTY($afterHandler)) return $data;

        return app($afterHandler)->shouldLogAfter($data);
    }
}
