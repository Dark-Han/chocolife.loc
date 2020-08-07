<?php

return [
    'sales/index' => [
        'type' => 'GET',
        'controller' => 'SaleController',
        'action' => 'index'
    ],
    'sales/[a-z0-9\-]*/\d*' => [
        'type' => 'GET',
        'controller' => 'SaleController',
        'action' => 'show'
    ],
    'sales/destroy' => [
        'type' => 'DELETE',
        'controller' => 'SaleController',
        'action' => 'destroy'
    ],
    'sales/store' => [
        'type' => 'POST',
        'controller' => 'SaleController',
        'action' => 'store'
    ]
];
