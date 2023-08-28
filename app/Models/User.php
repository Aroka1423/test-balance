<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credit($amount, $description = null): void
    {
        $this->increment('balance', $amount);

        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'credit',
            'description' => $description,
        ]);
    }

    public function debit($amount, $description = null): void
    {
        if ($this->balance < $amount) {
            throw new \Exception("Недостаточный баланс");
        }

        $this->decrement('balance', $amount);

        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'debit',
            'description' => $description,
        ]);
    }
}
