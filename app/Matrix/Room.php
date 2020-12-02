<?php

namespace App\Matrix;

use App\Matrix\AbstractResource;
use Illuminate\Support\Facades\Log;

/**
 * Room management
 *
 * This provides methods to create and update rooms
 *
 * @package Matrix\Resources
 */
class Room extends AbstractResource
{
    /**
     * The resource endpoint
     *
     * @internal
     * @var string
     */
    protected $endpoint = '';

    public function createDirect($alias)
    {
        if ($this->check()) {
            return $this->matrix()->request('POST', $this->endpoint('createRoom'), [
                    'preset' => 'public_chat',
                    'visibility' => 'private',
                    'is_direct' => true,
                    'room_alias_name' => $alias,
                    "creation_content" => [
                        "m.federate" => false
                    ],
                    'initial_state' => [[
                        'content' => [
                            'guest_access' => 'can_join'
                        ],
                        'type' => 'm.room.guest_access',
                        'state_key' => ''
                    ]]
                ], [
                    'access_token' => $this->data['access_token']
                ]);
        }
        throw new \Exception('Not authenticated');
    }

    public function invite($roomId, $userId){
        if ($this->check()){
            return $this->matrix()->request('POST', $this->endpoint('rooms/' . $roomId . '/invite'), [
                'user_id' => $userId
            ],[
                'access_token' => $this->data['access_token']
            ]);
        }
        throw new \Exception('Not invited');
    }

    public function join($roomId){
        if ($this->check()){
            return $this->matrix()->request('POST', $this->endpoint('join/'. $roomId), [
            ],[
                'access_token' => $this->data['access_token']
            ]);
        }
    }

    public function sendTextMessage($roomId, $message){
        $this->setData(session('matrix_data')); // Not sure why tghis needs to happen here after being done in the constructor
        Log::info("Sending a message to " . $roomId);
        if ($this->check()){
            $data = $this->matrix()->request('PUT', $this->endpoint('rooms/' . $roomId . '/send/m.room.message/' . rand(0,200) ), 
            [
                'msgtype' => "m.text",
                "body" => $message
            ],[
                'access_token' => session('matrix_access_token')
            ]);
            Log::info(print_r($data,true));
            return $data['event_id'];
        }
        Log::info("Failed check() when sending message to " . $roomId);
    } 

    public function getMessages($roomId, $from){
        $this->setData(session('matrix_data')); // Not sure why tghis needs to happen here after being done in the constructor
        $endpoint = "rooms/" . $roomId . "/messages?from=" . $from . "&dir=b";
        $returnData = $this->matrix()->request('GET', $this->endpoint($endpoint), [] , [
            'access_token' => session('matrix_access_token')
        ]);
        return $returnData;
    }
    public function getUnfilteredMessages($roomId){
        $endpoint = "rooms/" . $roomId . "/messages?dir=b";
        $returnData = $this->matrix()->request('GET', $this->endpoint($endpoint), [] , [
            'access_token' => session('matrix_access_token')
        ]);
        return $returnData;
    }
}
