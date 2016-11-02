<?php

namespace App\Logic\Role;


class RoleFactory
{
    public static $allRoles = [
        ROLE_ID_DEPT_MAKER,
        ROLE_ID_DEPT_MANAGER,
        ROLE_ID_DUE_DILIGENCE_MEMBER,
        ROLE_ID_SECRETARIAT,
        ROLE_ID_DEPUTY_COUNTRY_HEAD,
        ROLE_ID_REVIEW_COMMITTEE_MEMBER,
        ROLE_ID_SECRETARIAT_LEADER,
        ROLE_ID_REVIEW_DIRECTOR,
        ROLE_ID_APP_ADMIN,
        ROLE_ID_SYSTEM_ADMIN,
    ];

    public static function create($roleID)
    {
        switch ($roleID){
            case ROLE_ID_DEPT_MAKER: return new DeptMaker();
            case ROLE_ID_DEPT_MANAGER: return new DeptManager();
            case ROLE_ID_DUE_DILIGENCE_MEMBER: return new DueDiligenceMember();
            case ROLE_ID_SECRETARIAT: return new Secretariat();
            case ROLE_ID_DEPUTY_COUNTRY_HEAD: return new DeputyCountryHead();
            case ROLE_ID_REVIEW_COMMITTEE_MEMBER: return new ReviewCommitteeMember();
            case ROLE_ID_SECRETARIAT_LEADER: return new SecretariatLeader();
            case ROLE_ID_REVIEW_DIRECTOR: return new ReviewDirector();
            case ROLE_ID_APP_ADMIN: return new AppAdmin();
            case ROLE_ID_SYSTEM_ADMIN: return new SystemAdmin();
        }

    }
}