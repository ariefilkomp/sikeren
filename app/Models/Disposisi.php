<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
