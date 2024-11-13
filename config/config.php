<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Permissions
    |--------------------------------------------------------------------------
    |
    | This option defines the default permissions that will be assigned to a
    | new API token. These permissions will be used if no permissions are
    | provided when creating a new token.
    |
    */

    'default_permissions' => [
        'read',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Permissions
    |--------------------------------------------------------------------------
    |
    | This option defines the permissions that are available to be assigned to
    | an API token. You may change these permissions to any strings you like.
    |
    */

    'available_permissions' => [
        'create',
        'read',
        'update',
        'delete',
    ],
];