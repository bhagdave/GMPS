<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UseUuid;

class Group extends Model
{
  use UseUuid;

  public function creator(){
      return $this->belongsTo(User::class, 'created_user_id');
  }
}
