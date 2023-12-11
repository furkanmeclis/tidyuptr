<?php


use Illuminate\Support\Facades\Auth;

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
    function getAvatarUrl($email = "")
    {
        if($email == "") {
            if (Auth::guard('admin')->check()) {
                $email = Auth::guard('admin')->user()->email;
            } elseif (Auth::guard('organization')->check()) {
                $email = Auth::guard('organization')->user()->email;
            } elseif (Auth::guard('teacher')->check()) {
                $email = Auth::guard('teacher')->user()->email;
            } elseif (Auth::guard('student')->check()) {
                $email = Auth::guard('student')->user()->email;
            }
        }

        return "http://www.gravatar.com/avatar/capan?s=60";
    }

}
if(!function_exists('getLogoutUrl')) {
    function getLogoutUrl()
    {
            if (Auth::guard('admin')->check()) {
                return route('systemAdmin.logout');
            } elseif (Auth::guard('organization')->check()) {
                return route('organizationAdmin.logout');
            } elseif (Auth::guard('teacher')->check()) {
                return route('teacher.logout');
            } elseif (Auth::guard('student')->check()) {
                return route('student.logout');
            }
    }
}
if(!function_exists('getDayName')) {
    function getDayName($index = 0)
    {
        $days = [
            'Pazartesi',
            'Salı',
            'Çarşamba',
            'Perşembe',
            'Cuma',
            'Cumartesi',
            'Pazar',
        ];
        return $days[$index];
    }
}

if(!function_exists('getColumnFromKey')) {
    function getColumnFromKey($key)
    {
        $alphabet = range('A', 'Z');

        $column = '';
        $base = count($alphabet);

        while ($key >= 0) {
            $column = $alphabet[$key % $base] . $column;
            $key = floor($key / $base) - 1;
        }

        return $column;
    }
}
if(!function_exists('getPaperResize')) {
    function getPaperResize($ordinates, $width, $height)
    {
       $w = 297;
       $h = 421;
       $birimW = $w / $width;
       $birimH = $h / $height;
       return (object)[
           'x' => $ordinates['xs'] * $birimW,
           'y' => $ordinates['ys'] * $birimH,
           'width' => (($ordinates['xe'] - $ordinates['xs'] == 0) ? 1 : $ordinates['xe'] - $ordinates['xs']) * $birimW,
           'height' => (($ordinates['ye'] - $ordinates['ys'] == 0) ? 1 : $ordinates['ye'] - $ordinates['ys']) * $birimH,
       ];
    }
}
if(!function_exists('turkishToLower')) {
    function turkishToLower($string)
    {
        $lowercase = str_replace(
            ['Ç', 'Ğ', 'İ', 'I', 'Ö', 'Ş', 'Ü'],
            ['ç', 'ğ', 'i', 'ı', 'ö', 'ş', 'ü'],
            $string
        );

        return mb_strtolower($lowercase, 'UTF-8');
    }
}
