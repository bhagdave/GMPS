<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Auth;
use Mail;
use App\Group;
use App\User;
use App\Mail\InviteParticipant;

class ParticipantController extends Controller
{
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
                $this->attachInvitedUserToGroup($invitedUser, $group);
                return redirect()->back()->with('status', 'User added to group');
            }
            $signedUrl = $this->getSignedUrl($uuid, $request->input('email'));
            $this->sendInviteEmail($request->input('email'), $signedUrl);
            return redirect()->back()->with('status', 'Invite sent');
        }
        return redirect()->back()->with('status', 'Group not found');
    }

    private function getInvitedUser($email){
        return User::where('email', $email)->first();
    }

    private function attachInvitedUserToGroup($user, $group){
        $user->groups()->attach($group->id, [
            'type' => 'participant',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function getSignedUrl($group, $email){
        return  URL::temporarySignedRoute('group.accept', 
            now()->addDays(7),
            [
                'uuid' => $group, 
                'email' => $email
            ]
        );
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
}
