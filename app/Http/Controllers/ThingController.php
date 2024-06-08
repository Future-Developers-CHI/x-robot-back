<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThingRequest;
use App\Http\Requests\UpdateThingRequest;
use App\Models\Robot;
use App\Models\SearchThing;
use App\Models\Thing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function urlImage($url)
    {
        if (str_contains($url, "http")) {
            return $url;
        } else {
            return env('APP_URL', '').'storage/' . $url;
        }
    }
    public function index()
    {
        $things = Thing::where('robot_id', 1)->get();
        foreach ($things as $thing) {
            $thing->image_path = $this->urlImage($thing->image_path);
        }
        return $this->successResponse('Robot Objects founded', $things, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $path = $request->file('image')->store('product', 'public');
        // return $path;
        Thing::create([
            'robot_id'=>1,
            'image_path' => $path,
            'title' => $request->input('title'),
            'gps' => 'https://maps.app.goo.gl/dPWz3zVBekJpr2LM6',
        ]);
        return true;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image',
                'title' => 'required|string',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse('Please check the data sent!', $validator->errors(), 400);
        }
        $objectTitle = $request->input('title');
        $robot = Robot::find(1);
        $user = User::find(1);
        if ($robot->status == 'search') {
            $searchThings = SearchThing::where('robot_id', 1)->where('status', 0)->get();
            foreach ($searchThings as $thing) {
                if ($thing->title == $objectTitle) {
                    $thing->update(['status' => 1]);

                    $this->sendNotification($user,'we found'.$thing->title." is searched","Search",true);
                }
            }
        }
        $this->create($request);
        return $this->successResponse("Object successfully created");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'thing_id' => 'required|exists:things,id',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse('Please check the data sent!', $validator->errors(), 400);
        }
        Thing::destroy($request->input('thing_id'));
        return $this->successResponse('object successfully deleted');
    }
}
