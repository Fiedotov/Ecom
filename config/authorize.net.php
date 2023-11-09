<?php

return [
    'client_key' => env('VITE_AUTHORIZE_NET_CLIENT_KEY'),
    'api_login_id' => env('VITE_AUTHORIZE_NET_API_LOGIN_ID'),
    'transaction_key' => env('AUTHORIZE_NET_TRANSACTION_KEY'),
    'endpoint' => env('AUTHORIZE_NET_API_URL'),
    'developer_mode' => env('DEVELOPER_MODE', false),
];