<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Matrix\Matrix;
use App\Matrix\Room;

class RoomController extends Controller
{
    private $matrix;
    private $matrixRoom;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
        $this->matrixRoom = new Room($matrix);
    }

    public function index($uuid){
        $group = Group::find($uuid);
        $messages = $this->matrixRoom->getUnfilteredMessages($group->matrix_room_id);
        return view('room.index', compact('group', 'messages'));
    }

    public function sendMessage(Request $request, $uuid){
        $group = Group::find($uuid);
        $this->matrixRoom->sendTextMessage($group->matrix_room_id, $request->message);
        return redirect()->back()->with('status', "Message Sent");
    }
}
