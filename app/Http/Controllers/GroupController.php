<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
use Mail;
use App\Group;
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

    public function invite($uuid){
        $user = Auth::user();
        $group = Group::find($uuid);
        if (isset($group)){
            return view('groups.invite', compact('user', 'group'));
        }
        return redirect()->back()->with('status', 'Group not found');
    }

    public function sendInvite(Request $request, $uuid){
        $request->validate([
            'email' => 'required|unique:users,email|max:125'
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
            return "Accepted by $email for $group.";
        }
        abort(403);
    }
}
