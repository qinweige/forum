<?php

namespace App\Filters;

use App\User;

class ThreadsFilter extends Filters
{
    protected $filters = ['by', 'popular'];

    public function by($userName)
    {
        $user = User::where('name', $userName)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }
}