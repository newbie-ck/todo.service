<?php

namespace App\Helpers\Filterers;

use Illuminate\Http\Request;

class TodoFilterer 
{
    public static function apply(Request $filters, $todos)
    {
        if ($filters->has('todo_title') && !empty($filters->query('todo_title'))) {
            $todos->whereRaw(" LOWER(todo_title) like LOWER('%{$filters->query('todo_title')}%') ");
        }

        if ($filters->filled('is_completed') && $filters->query('is_completed') != -1) {
            $todos->where('is_completed', boolval($filters->query('is_completed')));
        }

        return $todos->orderBy($filters->query('sortBy') ?? 'created_at', $filters->query('orderBy') ?? 'desc')->get();
    }
}