<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

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
}
