<?php

return [
    'limit' => 15,
    'orderBy' => [
        [
            'column' => 'id',
            'direction' => 'desc'
        ]
    ],
    'excludedParameters' => ['api_token','buying_price'],
];
