<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Gate;
use Config;

class ProjectController extends Controller
{
    public function apply()
    {
        if (Gate::forUser($this->loginUser)->denies('apply-project')) {
            throw new AppException('PRJ001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        return view('project/apply')
                ->with('title', PAGE_NAME_PROJECT_APPLY)
                ->with('deptInfo', $this->loginUser->getDepartmentInfo())
                ->with('applicantInfo', $this->loginUser->getUserInfo())
                ->with('purchaseScopes', Config::get('constants.purchaseScopeNames'));
    }

    public function create()
    {
        if (Gate::forUser($this->loginUser)->denies('apply-project')) {
            throw new AppException('PRJ002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

    }

    public function display($id)
    {

    }
}
