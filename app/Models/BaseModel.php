<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function like(){
        return env('DB_CONNECTION') == 'pgsql'
                ? 'ILIKE'
                : 'LIKE';
    }
}
