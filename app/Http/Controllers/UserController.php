<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mail;
use Auth;
use App\User;
use App\Organisation;
use App\Mail\InviteUser;
use App\Matrix\Matrix;

class UserController extends Controller
{
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }
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

    public function accept($organisation, $email, Request $request){
        if ( $request->hasValidSignature() ){
            session([
                'organisation' => $organisation,
                'email' => $email
            ]);
            return view('user.accept');
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
        $organisation = $request->session()->get('organisation');
        $this->createUser($request, $email, $organisation);
        return redirect('login')->with('status', 'Registered - Please login');
    }

    private function createUser(Request $request, $email, $organisation){
        Log::info("CreateUser in UserController");
        $matrixUser = User::registerMatrixUser($request->input('name'), $email, $this->matrix);
        Log::info("CreateUser in UserController");
        return User::create([
            'name' => $request->input('name'),
            'nickname' => $request->input('nickname'),
            'organisation_id'=> $organisation,
            'email' => $email,
            'password' => Hash::make($request->input('password')),
            'matrix_user_id' => $matrixUser['user_id'],
            'matrix_device_id' => $matrixUser['device_id']
        ]);
    }
}
