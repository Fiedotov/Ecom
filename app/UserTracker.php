<?php

namespace App;

use Illuminate\Support\Facades\Session;

class UserTracker
{
    public const KEY_FIRST_PAGE = 'tracking_params';
    public const KEY_IP_ADDRESS = 'tracking_ip_address';
    public const KEY_PARAMS = 'tracking_params';
    public const KEY_REFERER = 'tracking_referer';
    public const KEY_USER_AGENT = 'tracking_user_agent';

    public static function getFirstPage(): null|string|array
    {
        return Session::get(static::KEY_FIRST_PAGE);
    }

    public static function getIpAddress(): ?string
    {
        return Session::get(static::KEY_IP_ADDRESS);
    }

    public static function getParams(): array
    {
        return Session::get(static::KEY_PARAMS, []);
    }

    public static function getReferer(): ?string
    {
        return Session::get(static::KEY_REFERER);
    }

    public static function getUserAgent(): ?string
    {
        return Session::get(static::KEY_USER_AGENT);
    }

    public static function queryString(): string
    {
        return http_build_query(self::getParams());
    }
}