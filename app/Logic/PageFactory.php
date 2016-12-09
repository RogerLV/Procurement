<?php

namespace App\Logic;

use App\Exceptions\AppException;
use App\Models\Page;

class PageFactory
{
    public static $pages = [
        ROUTE_NAME_ROLE_LIST => [
            'pageName' => PAGE_NAME_ROLE_LIST,
            'icon' => 'glyphicon-user',
        ],
        ROUTE_NAME_PROJECT_APPLY => [
            'pageName' => PAGE_NAME_PROJECT_APPLY,
            'icon' => 'glyphicon-shopping-cart',
        ],
        ROUTE_NAME_PROJECT_LIST => [
            'pageName' => PAGE_NAME_PROJECT_LIST,
            'icon' => 'glyphicon-list',
        ],
        ROUTE_NAME_REVIEW_APPLY => [
            'pageName' => PAGE_NAME_REVIEW_APPLY,
            'icon' => 'glyphicon-search'
        ],
        'ReviewMeetingList' => [
            'pageName' => PAGE_NAME_REVIEW_MEETING_LIST,
            'icon' => 'glyphicon-list'
        ],
    ];
    public static function create($routeName)
    {
        if (array_key_exists($routeName, self::$pages)) {
            $info = self::$pages[$routeName];
            return new Page($routeName, $info['pageName'], $info['icon']);
        }

        throw new AppException('ERR005', 'Unknown Page Name.');
    }
}