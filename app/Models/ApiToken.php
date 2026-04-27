<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'token', 'created_by', 'expires_at', 'actif'];

    protected $casts = [
        'actif' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generer(string $nom, int $userId)
    {
        return self::create([
            'nom' => $nom,
            'token' => Str::random(60),
            'created_by' => $userId,
            'expires_at' => Carbon::now()->addMonths(6),
            'actif' => true,
        ]);
    }

    public function estValide()
    {
        return $this->actif && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
