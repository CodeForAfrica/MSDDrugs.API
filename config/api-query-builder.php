<?php

return [
    'limit' => 15,
    'orderBy' => [
        [
            'column' => 'id',
            'direction' => 'desc'
        ]
    ],
    'excludedParameters' => ['api_token','measure','buying_price', 'checker_phone_number'],
];
