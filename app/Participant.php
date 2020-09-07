<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UseUuid;

class Participant extends Model
{
  use UseUuid;

  protected $fillable = [
      'group_id', 'user_id',
  ];

  public function user(){
      return $this->belongsTo(User::class);
  }
}
