<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Also log to Laravel's default log handler
    |--------------------------------------------------------------------------
    |
    | If "alsoLogInDefaultLog" the activity will also be logged in the default
    | Laravel logger handler
    |
    */
    'alsoLogInDefaultLog' => true,

    /*
    |--------------------------------------------------------------------------
    | Max age in months for log records
    |--------------------------------------------------------------------------
    |
    | When running the cleanLog-command all recorder older than the number of months
    | specified here will be deleted
    |
    */
    'deleteRecordsOlderThanMonths' => 2,

    /*
    |--------------------------------------------------------------------------
    | Fallback user id if no user is logged in
    |--------------------------------------------------------------------------
    |
    | If you don't specify a user id when logging some activity and no
    | user is logged in, this id will be used.
    |
    */
    'defaultUserId' => '',

    /*
    |--------------------------------------------------------------------------
    | Handler that is called before logging is done
    |--------------------------------------------------------------------------
    |
    | If you want to disable logging based on some custom conditions, create
    | a handler class that implements the BeforeHandlerInterface and
    | reference it here.
    |
    */
    'beforeHandler' => null,

    /*
    |--------------------------------------------------------------------------
    | Handler that is called after adding activity is done
    |--------------------------------------------------------------------------
    |
    | If you want to add functions to be done after logging, create
    | a handler class that implements the AfterHandlerInterface and
    | reference it here.
    |
    */
    'afterHandler' => null,

    /*
    |--------------------------------------------------------------------------
    | The class name for the related user model
    |--------------------------------------------------------------------------
    |
    | This can be a class name or null. If null the model will be determined
    | from Laravel's auth configuration.
    |
    */
    'userModel' => null,

    /*
    |--------------------------------------------------------------------------
    | The activity model to be used
    |--------------------------------------------------------------------------
    |
    | If you want to extend the activity model, specify here the namespace of
    | activity model to be used. If null the model will be determined from
    | Spatie's default model.
    | (e.g. 'modelPath' => 'App\Models\Activity')
    |
    */
    'modelPath' => null,
];
