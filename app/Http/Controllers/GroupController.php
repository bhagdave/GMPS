<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Group;

class GroupController extends Controller
{
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
        $group->save();
        return redirect('groups')->with('status', 'Group created');
    }
}
