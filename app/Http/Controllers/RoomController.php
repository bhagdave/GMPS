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
        ddd($syncData);
        $roomData = $syncData['rooms'];
        $thisRoomData = $roomData['join'][$group->matrix_room_id];
        $messages = $this->matrix->room->getMessages($group->matrix_room_id, 's26_57_0_1_1_1_1_62_1');
        return view('room.index', compact('group', 'messages', 'user', 'thisRoomData'));
    }

    public function sendMessage(Request $request, $uuid){
        $group = Group::find($uuid);
        $this->matrix->room->sendTextMessage($group->matrix_room_id, $request->message);
        return redirect()->back()->with('status', "Message Sent");
    }
}
