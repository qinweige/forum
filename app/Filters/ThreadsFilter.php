<?php

namespace App\Filters;

use App\User;

class ThreadsFilter extends Filters
{
    protected $filters = ['by'];

    public function by($userName)
    {
        $user = User::where('name', $userName)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
}