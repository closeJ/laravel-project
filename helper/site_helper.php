<?php

if (!function_exists('set_default')) {
    function set_default(&$name, $default = '')
    {
        if (isset($name)) {
            return $name;
        }

        return $default;
    }
}

//取得controller name
if (!function_exists('getCurrentControllerName')) {
    function getCurrentControllerName()
    {
        return getCurrentAction()[0];
    }
}

//取得method name
if (!function_exists('getCurrentMethodName')) {
    function getCurrentMethodName()
    {
        return getCurrentAction()[1];
    }
}

if (!function_exists('getCurrentAction')) {
    function getCurrentAction()
    {
        $action = \Route::current()->getActionName();
        $action = str_replace('App\Http\Controllers\\', '', $action);
        list($class, $method) = explode('@', $action);
        return [$class, $method];
    }
}

if (!function_exists('master')) {
    function master($datas = [])
    {
        return app('super',$datas);
    }
}
