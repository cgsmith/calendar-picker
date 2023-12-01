<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    public User $user;
    public Carbon $date;
}
