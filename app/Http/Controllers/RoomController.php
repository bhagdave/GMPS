<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use Auth;
use App\Matrix\Matrix;
use App\Matrix\Room;
use App\Matrix\UserSession;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    private $matrix;
    private $matrixRoom;
    private $userSession;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
        $this->userSession = new UserSession($matrix);
    }

    public function index($uuid){
        $user = Auth::user();
        $this->userSession->sync($user);
        $group = Group::find($uuid);
        $messages = $this->matrix->room->getMessages($group->matrix_room_id, 's26_57_0_1_1_1_1_62_1');
        return view('room.index', compact('group', 'messages', 'user'));
    }

    public function sendMessage(Request $request, $uuid){
        $group = Group::find($uuid);
        $data = $this->matrix->room->sendTextMessage($group->matrix_room_id, $request->message);
        Log::info(print_r($data, true));
        return redirect()->back()->with('status', "Message Sent");
    }
}
