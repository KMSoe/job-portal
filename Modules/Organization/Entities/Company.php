<?php
namespace Modules\Organization\Entities;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Storage\App\Classes\LocalStorage;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;

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

    protected $appends = ['logo_url', 'primary_phone', 'secondary_phone'];

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            $storage = new LocalStorage();
            return $storage->getUrl($this->logo);
        }
        return null;
    }

    public function getPrimaryPhoneAttribute()
    {
        return $this->phone_dial_code . $this->phone_no;
    }

    public function getSecondaryPhoneAttribute()
    {
        return $this->secondary_phone_dial_code . $this->secondary_phone_no;
    }

    public function getFoundedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
