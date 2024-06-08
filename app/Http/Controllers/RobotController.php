<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRobotRequest;
use App\Http\Requests\UpdateRobotRequest;
use App\Models\Robot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRobotRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

        $robot = Robot::find(1);
        return $this->successResponse('Robot Data',$robot);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'status' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse('Please check the data sent!', $validator->errors(), 400);
        }
        $status =  $request->get('status');
        if($status != 'search' && $status != 'explore')
            return $this->errorResponse('ابعت search او explore بس', 400);

        Robot::where('id', 1)->update(['status' => $status]);
        return $this->successResponse('The robots state has been successfully changed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Robot $robot)
    {
        //
    }
}
