<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class RoomController extends Controller
{
    public function index($uuid){
        $group = Group::find($uuid);
        return view('room.index', compact('group'));
    }
}
