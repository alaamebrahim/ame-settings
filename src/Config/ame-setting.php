<?php

return [
    'layout' => 'admin.layouts.app',
    'prefix' => 'admin',
    'middleware' => ['auth', 'verified'],
    'cache' => true
];
