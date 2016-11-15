<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\Project;
use App\Models\Vendor;
use Gate;

class VendorController extends Controller
{
    private $project;

    public function __construct()
    {
        parent::__construct();

        if (empty($projectID = trim(request()->input('projectid')))) {
            throw new AppException('VDRCTL001');
        }

        $this->project = Project::getIns($projectID);

        if (Gate::forUser($this->loginUser)->denies('score-template-editable', $this->project)) {
            throw new AppException('VDRCTL002', 'Cannot edit template at current phase.');
        }
    }

    public function add()
    {
        if (empty($vendorName = trim(request()->input('vendorName')))) {
            throw new AppException('VDRCTL003');
        }

        // check if its an existing vendor
        $target = Vendor::where([
            ['name', '=', $vendorName],
            ['type', '=', $this->project->scope]
        ])->first();

        if (is_null($target)) {
            // add to vendor table
            $target = new Vendor();
            $target->type = $this->project->scope;
            $target->name = $vendorName;
            $target->save();
        } else {
            // check if vendor already exists
            if ($this->project->vendors->contains($target)) {
                throw new AppException('VDRCTL004', 'Vendor already exists');
            }
        }

        $this->project->vendors()->attach($target);

        return response()->json([
            'status' => 'good',
            'info' => $target
        ]);
    }

    public function remove()
    {
        if (empty($vendorID = trim(request()->input('vendorid')))) {
            throw new AppException('VDRCTL005', 'Data Error.');
        }

        $vendor = Vendor::getIns($vendorID);

        $this->project->vendors()->detach($vendor);

        return response()->json(['status' => 'good']);
    }
}
