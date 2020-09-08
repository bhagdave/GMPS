<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
use Mail;
use App\Group;
use App\User;
use App\Particpant;
use App\Mail\InviteParticipant;

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
        $user->groups()->attach($group->id, [
            'type' => 'owner',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect('groups')->with('status', 'Group created');
    }

    public function view($uuid){
        $user = Auth::user();
        $group = Group::find($uuid);
        if (isset($group)){
            return view('groups.view', compact('user', 'group'));
        }
        return redirect()->back()->with('status', 'Group not found');
    }


    public function sendInvite(Request $request, $uuid){
        $request->validate([
            'email' => 'required|max:125'
        ]);
        $group = Group::find($uuid);
        if (isset($group)){
            $signedUrl =  URL::temporarySignedRoute('group.accept', 
                now()->addDays(7),
                [
                    'uuid' => $uuid, 
                    'email' => $request->input('email')
                ]
            );
            Mail::to($request->input('email'))
                ->send(new InviteParticipant(
                    [
                        'url' => $signedUrl
                    ]
                )
            );
            return redirect()->back()->with('status', 'Invite sent');
        }
        return redirect()->back()->with('status', 'Group not found');
    }

    public function accept($group, $email, Request $request){
        if ( $request->hasValidSignature() ){
            User::where('email', $email)->first();
            if (isset($user)){
                $particpant = $this->getParticipant();
            }
            session([
                'group' => $group,
                'email' => $email
            ]);
            return view('groups.accept');
        }
        abort(403);
    }

    private function getParticipantRecord($userId){
        return Particpant::where('',$userId)->first();
    }

    public function registerFromAccept(Request $request){
        $request->validate([
            'nickname' => 'required|unique:users|max:100',
            'name' => 'required|unique:users|max:100',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $email = $request->session()->get('email');
        $group = $request->session()->get('group');
        return "Email:$email and Group:$group";
    }
}
