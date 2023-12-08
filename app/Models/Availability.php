<?php
declare(strict_types=1);
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    public User|int $user;
    public Carbon $date;
}
