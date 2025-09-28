<?php

namespace Modules\Organization\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo',
        'name',
        'registration_name',
        'registration_no',
        'founded_at',
        'phone_dial_code',
        'phone_no',
        'secondary_phone_dial_code',
        'secondary_phone_no',
        'email',
        'secondary_email',
        'country_id',
        'city_id',
        'address',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'founded_at' => 'date',
    ];
    
    // --- Accessor for Logo ---
    
    /**
     * Get the full URL for the company logo.
     * * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        // Assuming 'logo' stores a relative path (e.g., 'logos/company-1.png')
        if ($this->logo) {
            // Use Laravel's Storage facade to get the URL for the 'public' disk
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo);
        }
        return null;
    }

    // --- Relationships ---
    
    /**
     * A Company has many Departments.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    // You would typically add relationships here for:
    // - Users (Employees)
    // - Country (belongsTo)
    // - City (belongsTo)
}
