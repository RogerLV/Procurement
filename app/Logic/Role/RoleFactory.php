<?php

namespace App\Logic\Role;


class RoleFactory
{
    public static function create($roleID)
    {
        switch ($roleID){
            case 1: return new DeptMaker();
            case 2: return new DeptManager();
            case 3: return new DueDiligenceMember();
            case 4: return new Secretariat();
            case 5: return new DeputyCountryHead();
            case 6: return new ReviewCommitteeMember();
            case 7: return new SecretariatLeader();
            case 8: return new ReviewDirector();
            case 9: return new AppAdmin();
            case 10: return new SystemAdmin();
        }

    }
}