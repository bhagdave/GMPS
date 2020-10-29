<?php

namespace App\Http\Controllers;

use Illuzaminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Matrix\Matrix;
use App\Matrix\Room;
use Auth;
use Mail;
use App\Group;
use App\User;
use App\Mail\InviteParticipant;

class ParticipantController extends Controller
{
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
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
            'email' => 'required|max:125'
        ]);
        $group = Group::find($uuid);
        if (isset($group)){
            $invitedUser = $this->getInvitedUser($request->input('email'));
            if (isset($invitedUser)){
                $this->attachInvitedUserToGroup($invitedUser, $uuid);
                return redirect()->back()->with('status', 'User added to group');
            }
            $signedUrl = $this->getSignedUrl('participant.accept', $uuid, $request->input('email'));
            $this->sendInviteEmail($request->input('email'), $signedUrl);
            return redirect()->back()->with('status', 'Invite sent');
        }
        return redirect()->back()->with('status', 'Group not found');
    }

    private function getInvitedUser($email){
        return User::where('email', $email)->first();
    }

    private function attachInvitedUserToGroup($user, $group){
        $user->groups()->attach($group, [
            'type' => 'participant',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }


    private function sendInviteEmail($email, $signedUrl){
        Mail::to($email)
            ->send(new InviteParticipant(
                [
                    'url' => $signedUrl
                ]
            )
        );
    }

    public function accept($group, $email, Request $request){
        if ( $request->hasValidSignature() ){
            session([
                'group' => $group,
                'email' => $email
            ]);
            return view('participants.accept');
        }
        abort(403);
    }

    public function registerFromAccept(Request $request){
        $request->validate([
            'nickname' => 'required|unique:users|max:100',
            'name' => 'required|unique:users|max:100',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $email = $request->session()->get('email');
        $group = $request->session()->get('group');
        $user = $this->createUser($request, $email);
        $this->attachInvitedUserToGroup($user, $group);
        $this->joinMatrixRoom($user, $group);
        return redirect('login')->with('status', 'Registered - Please login');
    }

    private function joinMatrixRoom($user, $group){
        Auth::login($user);
        $groupModel = Group::find($group);
        $room = new Room($this->matrix);
        $room->join($groupModel->matrix_room_alias);
        Auth::logout($user);
    }

    private function createUser(Request $request, $email){
        $matrixUser = User::registerMatrixUser($request->input('name'), $request->input('nickname'), $this->matrix);
        return User::create([
            'name' => $request->input('name'),
            'nickname' => $request->input('nickname'),
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'matrix_user_id' => $matrixUser['user_id'],
            'matrix_device_id' => $matrixUser['device_id']
        ]);
    }
    public function remove($group, $participant){
        $user = User::find($participant);
        $message = "Removed->" . $user->name;
        $user->groups()->detach($group);
        return redirect()->back()->with('status', $message);
    }
}
