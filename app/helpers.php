<?php


if(!function_exists('getActiveUser')) {
    function getActiveUser()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('organization')->check()) {
            return Auth::guard('organization')->user();
        } elseif (Auth::guard('teacher')->check()) {
            return Auth::guard('teacher')->user();
        } elseif (Auth::guard('student')->check()) {
            return Auth::guard('student')->user();
        }
    }
}
if(!function_exists('getAvatarUrl')) {
    function getAvatarUrl()
    {
        $name = "";
        if (Auth::guard('admin')->check()) {
            $name= Auth::guard('admin')->user()->name;
        } elseif (Auth::guard('organization')->check()) {
            $name= Auth::guard('organization')->user()->name;
        } elseif (Auth::guard('teacher')->check()) {
            $name= Auth::guard('teacher')->user()->name;
        } elseif (Auth::guard('student')->check()) {
            $name= Auth::guard('student')->user()->name;
        }
        return "https://ui-avatars.com/api/?name=" . urlEncode($name) . "&background=random&size=256";
    }
}
