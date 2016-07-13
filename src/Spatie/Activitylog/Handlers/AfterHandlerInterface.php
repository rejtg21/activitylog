<?php

namespace Spatie\Activitylog\Handlers;

interface AfterHandlerInterface
{
    /**
     * Call to the log will only be made if this function returns true.
     *
     * @param array   $data   sets of data to be pass after inserting in activity
     *
     * @return $data sent by user and activity object added 
     *  e.g. $data->activity
     */
    public function shouldLogAfter($data);
}
