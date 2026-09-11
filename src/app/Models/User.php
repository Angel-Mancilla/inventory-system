<?php

namespace App\Models;

use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $google_id
 * @property string|null $provider
 * @property \Illuminate\Support\Carbon|null $join_date
 * @property Carbon|null $last_login
 * @property string|null $phone_number
 * @property UserStatus $status
 * @property string|null $avatar
 * @property string|null $position
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable([
    'name', 'email', 'google_id', 'provider', 'join_date', 'last_login',
    'phone_number', 'status', 'avatar', 'position', 'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'date',
            'last_login' => 'datetime',
            'status' => UserStatus::class,
        ];
    }

    public function estaActivo(): bool
    {
        return $this->status === UserStatus::Activo;
    }

    public function iniciaSesionConGoogle(): bool
    {
        return $this->provider === 'google' && filled($this->google_id);
    }
}