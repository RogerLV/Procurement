<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Logic\Role\RoleFactory;
use App\Logic\System\Util;
use App\Models\Department;
use App\Models\SystemRole;
use App\Models\User;
use App\Models\UpdateLog as Log;

class RoleController extends Controller
{
    public function listPage()
    {
        // render role options
        $roleOptions = [];
        foreach (RoleFactory::$allRoles as $roleID) {
            $roleIns = RoleFactory::create($roleID);
            if ($roleIns->canDisplay()) {
                $roleOptions[$roleID] = $roleIns;
            }
        }

        if (!empty(request()->input('roleid'))) {
            $roleID = request()->input('roleid');

            if (!is_numeric($roleID)) {
                throw new AppException('ERR006', 'Incorrect paramters.');
            }

            $roleEntries = SystemRole::where('roleID', $roleID)->get();
            $lanIDs = $roleEntries->pluck('lanID')->toArray();

            return view('role/list')
                    ->with('title', PAGE_NAME_ROLE_LIST)
                    ->with('roleOptions', $roleOptions)
                    ->with('roleEntries', $roleEntries)
                    ->with('userInfo', User::whereIn('lanID', $lanIDs)->get()->keyBy('lanID'))
                    ->with('deptInfo', Department::all()->keyBy('dept'))
                    ->with('editable', $this->editable($roleID))
                    ->with('selectedRole', RoleFactory::create($roleID));
        } else {
            return view('role/empty')
                    ->with('title', PAGE_NAME_ROLE_LIST)
                    ->with('roleOptions', $roleOptions);
        }
    }

    public function remove()
    {
        if (empty($mapID = request()->input('mapid'))) {
            throw new AppException('ERR007', 'Data Error');
        }

        $mapIns = SystemRole::find($mapID);

        if (is_null($mapIns) || !$this->editable($mapIns->roleID)) {
            throw new AppException('ERR008', 'Incorrect Role Info.');
        }

        Log::logDelete($mapIns);
        $mapIns->delete();

        return response()->json(['status' => 'good']);
    }

    public function add()
    {
        if (empty($lanID = request()->input('lanid'))
            || empty($dept = request()->input('dept'))
            || empty($roleID = request()->input('roleid'))) {
            throw new AppException('ERR009', 'Data Error');
        }

        if (!$this->editable($roleID)) {
            throw new AppException('ERR010', 'Incorrect Role Info.');
        }

        // check existence
        $result = SystemRole::where([
            ['lanID', '=', $lanID],
            ['dept', '=', $dept],
            ['roleID', '=', $roleID],
        ])->first();

        if (!is_null($result)) {
            throw new AppException('ERR011', 'Role Already Exists.');
        }

        // add role
        $roleIns = new SystemRole();
        $roleIns->lanID = $lanID;
        $roleIns->dept = $dept;
        $roleIns->roleID = $roleID;
        $roleIns->save();

        Log::logInsert($roleIns);

        return response()->json(['status' => 'good']);
    }

    public function select()
    {
        if (empty($mapID = request()->input('mapid'))) {
            throw new AppException('ERR009', 'Data Error.');
        }

        $this->loginUser->setActiveRole($mapID);

        return response()->json(['status' => 'good']);
    }


    private function editable($targetRoleID)
    {
        return Util::checkRole(ROLE_ID_APP_ADMIN)
            && RoleFactory::create($targetRoleID)->canOperate();
    }
}
