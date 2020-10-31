<?php

namespace App\Matrix;

use App\Matrix\AbstractResource;

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
        if ($this->check()){
            $data = $this->matrix()->request('PUT', $this->endpoint('rooms/' . $roomId . '/send/m.room.message/' . rand(0,200) ), 
            [
                'msgtype' => "m.text",
                "body" => $message
            ],[
                'access_token' => $this->data['access_token']
            ]);
            return $data['event_id'];
        }
    } 

}
