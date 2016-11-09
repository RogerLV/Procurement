<?php

namespace App\Logic;


use App\Exceptions\AppException;
use App\Models\Department;

class DepartmentKeeper
{
    private static $departmentInfoStack;

    public static function getDeptInfo($dept = 'all')
    {
        $departmentInfo = self::getDepartmentInfoStack();

        if (is_array($dept)) {
            return $departmentInfo->whereIn('dept', $dept);
        } elseif ('all' == $dept) {
            return $departmentInfo;
        } elseif (array_key_exists($dept, $departmentInfo)) {
            return $departmentInfo[$dept];
        } else {
            throw new AppException('DEPT001', 'Incorrect Department Info.');
        }
    }

    public static function getDeptKeys()
    {
        $departmentInfo = self::getDepartmentInfoStack();

        return $departmentInfo->keys()->toArray();
    }

    private static function getDepartmentInfoStack()
    {
        if (is_null(self::$departmentInfoStack)) {
            self::$departmentInfoStack = Department::all()->keyBy('dept');
        }

        return self::$departmentInfoStack;
    }
}