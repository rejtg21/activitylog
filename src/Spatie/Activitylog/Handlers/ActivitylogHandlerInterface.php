<?php

namespace Spatie\Activitylog\Handlers;

interface ActivitylogHandlerInterface
{
    /**
     * Log some activity.
     *
     * @param $text
     * @param array/object $data - additional data to be sent handlers
     *
     * @return bool
     */
    public function log($data);

    /**
     * Clean old log records.
     *
     * @param int $maxAgeInMonths
     *
     * @return bool
     */
    public function cleanLog($maxAgeInMonths);
}
