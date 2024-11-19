<?php

namespace App\Http\Documentations\Requests;

/**
 * @OA\Schema(
 *      title="Todo Create",
 *      description="Request body for create todo",
 *      required={"todo_title"}
 * )
 */
class TodoCreate
{
    /**
     * @OA\Property(
     *      title="todo_title",
     *      description="todo's title",
     *      example="Task 1",
     * )
     *
     * @var string
     */
    private $todo_title;
}
