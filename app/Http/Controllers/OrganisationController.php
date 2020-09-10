<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organisation;

class OrganisationController extends Controller
{
    public function view($uuid){
        $organisation = Organisation::find($uuid);
        if (isset($organisation)){
            $users = $organisation->users;
            return view('organisation.view', compact('organisation', 'users'));
        }
        return redirect()->back()->with('status', 'Group not found');
    } 
}
