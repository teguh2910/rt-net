<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is an admin RT (full access)
     */
    public function isAdminRT(): bool
    {
        return $this->role === 'admin_rt';
    }

    /**
     * Check if user is Ketua RT (management access)
     */
    public function isKetuaRT(): bool
    {
        return $this->role === 'ketua_rt';
    }

    /**
     * Check if user is Bendahara (finance access)
     */
    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    /**
     * Check if user is Warga (view-only access)
     */
    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    /**
     * Check if user has admin or ketua privileges
     */
    public function canManage(): bool
    {
        return in_array($this->role, ['admin_rt', 'ketua_rt']);
    }

    /**
     * Check if user can manage finances
     */
    public function canManageFinances(): bool
    {
        return in_array($this->role, ['admin_rt', 'ketua_rt', 'bendahara']);
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class);
    }

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function digitalLetters(): HasMany
    {
        return $this->hasMany(DigitalLetter::class, 'issued_by');
    }

    public function financialReports(): HasMany
    {
        return $this->hasMany(FinancialReport::class, 'created_by');
    }
}
