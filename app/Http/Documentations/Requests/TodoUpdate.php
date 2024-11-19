<?php

namespace App\Http\Documentations\Requests;

/**
 * @OA\Schema(
 *      title="Todo Update",
 *      description="Request body for update todo",
 *      required={"todo_title"}
 * )
 */
class TodoUpdate
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
