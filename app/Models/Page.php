<?php

namespace App\Models;


class Page
{
    public function __construct($routeName, $name, $icon = null)
    {
        $this->routeName = $routeName;
        $this->name = $name;
        $this->icon = $icon;
        $this->url = route($routeName);
    }

    public function __get($attr)
    {
        return $this->$attr;
    }
}