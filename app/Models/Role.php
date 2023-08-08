<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'permissions_id',
        'name',
        'status',
        'is_del'
    ];

    /**
     * @return string
     */
    public function rolePermissions(): string
    {
        $permissionIds = json_decode($this['permission_ids']);
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');
        return implode(', ', $permissions->toArray());
    }


    /**
     * @return HasMany
     */
    public function parentRoleUsers(): HasMany
    {

        return $this->hasMany(User::class, 'role_id', 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {

        return $this->hasMany(User::class, 'role_id', 'id')->where('is_del', 0);
    }

    /**
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $permission && in_array($permission->id, json_decode($this->permission_ids));
    }

}
