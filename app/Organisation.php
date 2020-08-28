<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UseUuid;

class Organisation extends Model
{
  use UseUuid;

  protected $guarded = []; 
}
