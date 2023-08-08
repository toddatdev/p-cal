<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name',
        'job_title',
        'start_date',
        'end_date',
        'hourly_rate',
        'sales_person_id',
        'type_id',
        'platform_id',
        'user_id',
        'commission_percentage_employee',
        'commission_percentage_manager',
        'commission_percentage_hod',
        'status',
        'is_del',
    ];

    /**
     * @return HasOne
     */
    public function commission (): HasOne
    {

        return $this->hasOne(ProjectCommissions::class, 'project_id', 'id')->where(['status' => 0, 'is_del' => 0])->orderBy('id', 'DESC');
    }

    /**
     * @return HasOne
     */
    public function sale(): \Illuminate\Database\Eloquent\Relations\HasOne
    {

        return $this->hasOne(AdminSetting::class, 'id', 'sales_person_id')->where('setting_type', 'sales_person');
    }

    /**
     * @return HasOne
     */
    public function type(): HasOne
    {

        return $this->hasOne(AdminSetting::class, 'id', 'type_id')->where('setting_type', 'type');
    }

    /**
     * @return HasOne
     */
    public function platform(): HasOne
    {

        return $this->hasOne(AdminSetting::class, 'id', 'platform_id')->where('setting_type', 'platform');
    }

    /**
     * @return mixed
     */
    public function projectEmployee(): mixed
    {

        $projectEmployeeID = ProjectUser::where('project_id', $this->id)->pluck('user_id')->first();
        return User::where('id', $projectEmployeeID)->first();
    }


    /**
     * @return HasMany
     */
    public function earnings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Earning::class);
    }

    /**
     * @return mixed
     */
    public function getTotalEarningAttribute(): mixed
    {

        $earning = Earning::where('status', 0)->where('is_del', 0)->where('project_id', $this->id)->sum('earning');
        return '$ '.number_format($earning, 2);
    }

    /**
     * @return HasMany
     */
    public function projectUser(): HasMany
    {

        return $this->hasMany(ProjectUser::class, 'project_id');
    }

}
