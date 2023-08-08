<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StopEarningApproval extends Model
{
    use HasFactory;

    public function user() {

        return $this->hasOne(User::class, 'id', 'requested_by');
    }

    public function earner() {

        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function project() {

        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
