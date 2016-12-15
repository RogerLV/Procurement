<?php

namespace App\Http\Controllers;


use App\Exceptions\AppException;
use App\Models\ProjectRoleDepartment;

class MemberDepartmentController extends StageController
{
    public function __construct()
    {
        parent::__construct();

        if (STAGE_ID_INVITE_DEPT !=  $this->projectIns->stage) {
            throw new AppException('MBRDEPTCTL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }
    }

    public function add()
    {
        if (empty($dept = trim(request()->input('dept')))
            || empty($amount = trim(request()->input('memberCount')))) {
            throw new AppException('MBRDEPTCTL002');
        }

        if (!is_null($this->projectIns->memberDepts()->where('dept', $dept)->first())) {
            throw new AppException('MBRDEPTCTL004', 'Department is already selected');
        }

        $memberDept = new ProjectRoleDepartment();
        $memberDept->dept = $dept;
        $memberDept->memberAmount = $amount;

        $this->projectIns->memberDepts()->save($memberDept);

        return response()->json([
            'status' => 'good',
            'info' => [
                'memberDept' => $memberDept,
                'deptInfo' => $memberDept->department
            ]
        ]);
    }

    public function remove()
    {
        if (empty($memberDeptID = trim(request()->input('memberDeptID')))) {
            throw new AppException('MBRDEPTCTL003');
        }

        $memberDeptIns = ProjectRoleDepartment::getIns($memberDeptID);
        $memberDeptIns->delete();

        return response()->json(['status' => 'good']);
    }
}
