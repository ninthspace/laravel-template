<?php

/*
 * You can place your custom package configuration in here.
 */

return [
    // the Eloquent model that represents the User
    'user_model'                         => App\User::class,

    // the Eloquent model that represents the Tenant
    'tenant_model'                       => Ninthspace\Dweller\Tenant\Tenant::class,

    // the respective table names for User and Tenant
    'table_names'                        => [
        'users_table'   => 'users',
        'tenants_table' => 'tenants',
    ],

    // whether an e-mail address can belong to multiple tenants
    // in traditional applications, the User is uniquely identified
    // by their e-mail address. With Dweller, we change this uniqueness
    // to include the tenant_id
    'multiple_tenants_per_email_address' => true,
];
