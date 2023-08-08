<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectUser extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function role(): HasOne
    {

        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    /**
     * @return HasOne
     */
    public function parentUser (): HasOne
    {

        return $this->hasOne(User::class, 'id', 'parent_id');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {

        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

}
