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
                    'preset' => 'trusted_private_chat',
                    'visibility' => 'private',
                    'is_direct' => true,
                    'room_alias_name' => $alias,
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

}
