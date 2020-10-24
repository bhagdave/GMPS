<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Matrix\Matrix;
use App\Matrix\Room;
use Illuminate\Support\Facades\Log;
use App\Group;

class GroupController extends Controller
{
    protected $matrix;

    public function __construcT(Matrix $matrix){
        $this->matrix = $matrix;
    }

    public function index(){
        $user = Auth::user();
        return view('groups.index', compact('user'));
    }

    public function add(){
        $user = Auth::user();
        return view('groups.add', compact('user'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $request->validate([
            'name' => 'required|unique:groups|max:100'
        ]);
        $group = new Group([
            'name' => request('name'),
            'created_user_id' => $user->id
        ]);
        $roomDetails = $this->createRoom($request->input('name'));
        $group->matrix_room_id = $roomDetails['room_id'];
        $group->matrix_room_alias = $roomDetails['room_alias'];
        $group->save();
        $user->groups()->attach($group->id, [
            'type' => 'owner',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect('groups')->with('status', 'Group created');
    }

    private function createRoom($alias){
        $room = new Room($this->matrix);
        $roomDetails = $room->createDirect($alias);
        Log::debug(print_r($roomDetails, true));
        return $roomDetails;
    }

    public function view($uuid){
        $user = Auth::user();
        $group = Group::find($uuid);
        if (isset($group)){
            return view('groups.view', compact('user', 'group'));
        }
        return redirect()->back()->with('status', 'Group not found');
    }

}
