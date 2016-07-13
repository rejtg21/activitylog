<?php

namespace Spatie\Activitylog;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Auth\Guard;
use Spatie\Activitylog\Handlers\BeforeHandler;
use Spatie\Activitylog\Handlers\DefaultLaravelHandler;
use Request;
use Config;

class ActivitylogSupervisor
{
    /**
     * @var array logHandlers
     */
    protected $logHandlers = [];

    protected $auth;

    protected $config;

    /**
     * Create the logsupervisor using a default Handler
     * Also register Laravels Log Handler if needed.
     *
     * @param Handlers\ActivitylogHandlerInterface $logHandler
     * @param Repository                           $config
     * @param Guard                                $auth
     */
    public function __construct(Handlers\ActivitylogHandlerInterface $logHandler, Repository $config, Guard $auth)
    {
        $this->config = $config;

        // this handler is the one saving to db
        // added key for data to be pass by reference
        $this->logHandlers['activity'] = $logHandler;

        if ($this->config->get('activitylog.alsoLogInDefaultLog')) {
            $this->logHandlers[] = new DefaultLaravelHandler();
        }

        $this->auth = $auth;
    }

    /**
     * Log some activity to all registered log handlers.
     *
     * @param $text
     * @param array/object $data - additional data to be sent handlers
     *
     * @return bool
     */
    public function log($text, $data = array())
    {
        // there is no catcher here expected to be catch on one of the handler
        // convert the data to object if its an array
        $data = (object) $data;
        $data->user_id = !EMPTY($data->user_id) ? $data->user_id : '';

        $data->user_id = $this->normalizeUserId($data);
        $data->text = $text;

        if (! $this->shouldLogCall($data)) {
            return false;
        }

        $data->ip_address = Request::getClientIp();

        foreach ($this->logHandlers as $logHandler) {
            $logHandler->log($data);
        }
        
        return $data->activity;
    }

    /**
     * Clean out old entries in the log.
     *
     * @return bool
     */
    public function cleanLog()
    {
        foreach ($this->logHandlers as $logHandler) {
            $logHandler->cleanLog(Config::get('activitylog.deleteRecordsOlderThanMonths'));
        }

        return true;
    }

    /**
     * Normalize the user id.
     *
     * @param object|int $userId
     *
     * @return int
     */
    public function normalizeUserId($userId)
    {

        if (is_numeric($userId)) {
            return $userId;
        }

        if ($this->auth->check()) {
            return $this->auth->user()->id;
        }

        if (is_numeric($this->config->get('activitylog.defaultUserId'))) {
            return $this->config->get('activitylog.defaultUserId');
        };

        return '';
    }

    /**
     * Determine if this call should be logged.
     *
     * @param $text
     * @param $userId
     *
     * @return bool
     */
    protected function shouldLogCall($data)
    {
        $beforeHandler = $this->config->get('activitylog.beforeHandler');

        if (is_null($beforeHandler) || $beforeHandler == '') {
            return true;
        }

        return app($beforeHandler)->shouldLog($data);
    }
}
