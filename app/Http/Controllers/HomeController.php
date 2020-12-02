<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Matrix\Matrix;
use App\Matrix\UserData;
use App\Matrix\UserSession;
use App\User;
use App\Group;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    private $matrix;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->middleware('auth');
        $this->matrix = $matrix;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $joinedRooms = $this->getJoinedRooms();
        $invitedRooms = $this->getInvitedRooms();
        return view('home', compact('user', 'joinedRooms', 'invitedRooms'));
    }

    private function getJoinedRooms(){
        if (null != session('matrix_sync')){
            $joined = session('matrix_sync')['rooms']['join'];
            $joinedIds = array_keys($joined);
            $groups = Group::whereIn('matrix_room_id',$joinedIds)->get();
            return ["rooms" => $joined, "groups" => $groups];
        }
        return null;
    }
    private function getInvitedRooms(){
        if (null != session('matrix_sync')){
            $invited = session('matrix_sync')['rooms']['invite'];
            $invited = array_keys($invited);
            $groups = Group::whereIn('matrix_room_id',$invited)->get();
            return $groups;
        }
        return null;
    }
    public function profile(){
        $user = Auth::user();
        return view('profile', compact('user'));
    }
    public function saveProfile(Request $request){
        $user = Auth::user();
        $request->validate([
            'nickname' => [
                'max:100',
                'nullable',
                Rule::unique('users')->ignore($user->id, 'id')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id, 'id')
            ] 
        ]);
        $user->nickname = request('nickname');
        $user->email = request('email');
        $user->save();
        return redirect('home')->with('status', 'Profile Updated');
    }
}
