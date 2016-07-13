<?php

namespace Spatie\Activitylog\Handlers;

use Log;

class DefaultLaravelHandler implements ActivitylogHandlerInterface
{
    /**
     * Log activity in Laravels log handler.
     *
     * @param string $text
     * @param $userId
     * @param array  $attributes
     *
     * @return bool
     */
    public function log($data)
    {
        // temporary
        $userId = $data->user_id;
        unset($data->user_id);
        $logText = $data->text;
        unset($data->text);

        $logText .= ($userId != '' ? ' (by user_id '.$userId.')' : '');
        // $logText .= (count($data)) ? PHP_EOL.print_r($data, true) : '';

        Log::info($logText);

        return true;
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
        //this handler can't clean it's records

        return true;
    }
}
