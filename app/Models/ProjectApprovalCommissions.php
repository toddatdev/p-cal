<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectApprovalCommissions extends Model
{
    use HasFactory;

    /**
     * @return HasOne
     */
    public function project (): \Illuminate\Database\Eloquent\Relations\HasOne
    {

        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {

        return $this->hasOne(User::class, 'id', 'requested_by');
    }
}
