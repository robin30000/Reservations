<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'user_id',
        'site_id',
        'start_date',
        'end_date',
    ];

    // App\Models\Reservation.php
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function site() {
        return $this->belongsTo(Site::class);
    }

}
