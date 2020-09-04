<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
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
