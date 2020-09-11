<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Mail;
use Auth;
use App\User;
use App\Organisation;
use App\Mail\InviteUser;

class UserController extends Controller
{
    public function delete(Request $request){
        $user = Auth::user();
        if ($user->main){
            $user = User::find($request->input('id'));
            $message = "Deleted->" . $user->name;
            $user->delete();
            return redirect()->back()->with('status', $message);
        }
        return redirect()->back()->with('status', 'You are not authorised to do that');
    }

    public function sendInvite(Request $request, $uuid){
        $request->validate([
            'email' => 'required|max:125'
        ]);
        $organisation = Organisation::find($uuid);
        if (isset($organisation)){
            $invitedUser = User::where('email', $request->input('email'))->first();
            if (isset($invitedUser)){
                if (isset($invitedUser->organisation_id)){
                    return redirect()->back()->with('status', 'That user is already attached to an organisation');
                }
                $this->attachInvitedUserToOrganisation($invitedUser, $uuid);
                return redirect()->back()->with('status', 'User added to organisation');
            }
            $signedUrl = $this->getSignedUrl('user.accept', $uuid, $request->input('email'));
            $this->sendInviteEmail($request->input('email'), $signedUrl);
            return redirect()->back()->with('status', 'Invite sent');
        }
        return redirect()->back()->with('status', 'Organisation not found');
    }

    private function attachInvitedUserToOrganisation($invitedUser, $organisationId){
        $invitedUser->organisation_id = $organisationId;
        $invitedUser->save();
    }

    private function sendInviteEmail($email, $signedUrl){
        Mail::to($email)
            ->send(new InviteUser(
                [
                    'url' => $signedUrl
                ]
            )
        );
    }
}
