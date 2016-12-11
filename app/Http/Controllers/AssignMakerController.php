<?php

namespace App\Http\Controllers;


use App\Models\ProjectRole;
use App\Models\ProjectRoleDepartment;
use App\Exceptions\AppException;
use App\Models\UpdateLog as Log;

class AssignMakerController extends StageController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add()
    {
        if (empty($lanID = request()->input('lanID'))
            || empty($projectDeptID = request()->input('projectDeptID'))) {
            throw new AppException('ASMK001', 'Data Error.');
        }

        // existence check
        $existence = ProjectRole::where([
            ['roleDeptID', '=', $projectDeptID],
            ['lanID', '=', $lanID],
        ])->first();
        if (!is_null($existence)) {
            throw new AppException('ASMK002', 'Role already assigned.');
        }

        $projectDeptIns = ProjectRoleDepartment::find($projectDeptID);
        if (is_null($projectDeptIns)) {
            throw new AppException('ASMK003', 'Data Error.');
        }

        if ($projectDeptIns->role()->count() >= $projectDeptIns->memberAmount) {
            throw new AppException('ASMK006', 'Member amount reaches limit. ');
        }

        $projectRole = new ProjectRole();
        $projectRole->lanID = $lanID;
        $projectDeptIns->role()->save($projectRole);
        Log::logInsert($projectRole);

        return response()->json([
            'status' => 'good',
            'info' => [
                'projectRole' => $projectRole
            ],
        ]);
    }

    public function remove()
    {
        if (empty($projectRoleID = request()->input('projectroleid'))) {
            throw new AppException('ASMK004', 'Data Error.');
        }

        $projectRoleIns = ProjectRole::find($projectRoleID);
        if (is_null($projectRoleIns)) {
            throw new AppException('ASMK005', 'Data Error.');
        }

        Log::logDelete($projectRoleIns);
        $projectRoleIns->delete();

        return response()->json(['status' => 'good']);
    }
}
