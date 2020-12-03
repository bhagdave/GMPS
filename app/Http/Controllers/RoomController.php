<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use Auth;
use App\Matrix\Matrix;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    private $matrix;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }

    public function index($uuid){
        $user = Auth::user();
        $group = Group::find($uuid);
        $syncData = $this->matrix->session->sync($user);
        $roomEvents = $this->getRoomEvents($syncData, $group);
        return view('room.index', compact('group', 'user', 'roomEvents'));
    }

    private function getRoomEvents($syncData, $group){
        $roomData = $syncData['rooms'];
        return $roomData['join'][$group->matrix_room_id]['timeline']['events'];
    }

    public function sendMessage(Request $request, $uuid){
        $group = Group::find($uuid);
        $this->matrix->room->sendTextMessage($group->matrix_room_id, $request->message);
        return redirect()->back()->with('status', "Message Sent");
    }
}
