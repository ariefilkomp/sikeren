<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'no_hp',
        'bidang_id',
        'atasan_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function team()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }

    public function aktivitas()
    {
        $aktivitas_ids = $this->peserta()->pluck('aktivitas_id');
        $aktivitas = Aktivitas::whereIn('id', $aktivitas_ids)->get();
        return $aktivitas;
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
