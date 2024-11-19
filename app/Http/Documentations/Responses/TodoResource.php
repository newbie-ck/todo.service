<?php

namespace App\Http\Documentations\Responses;

/**
 * @OA\Schema(
 *     title="Todo",
 *     description="Todo data",
 * )
 */
class TodoResource
{
    /**
     * @OA\Property(
     *      title="uuid",
     *      description="todo's uuid",
     *      example="f8928d56-6c89-4c51-881a-9f1ed607f421",
     * )
     *
     * @var string
     */
    private $uuid;

    /**
     * @OA\Property(
     *      title="title",
     *      description="todo's title",
     *      example="Task 1",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *      title="is_completed",
     *      description="indicator of the todo's completeness (true - completed, false - non-complete)",
     *      example=true,
     * )
     *
     * @var boolean
     */
    private $is_completed;

    /**
     * @OA\Property(
     *      title="created_at",
     *      description="record creation date time",
     *      example="2024-01-01 00:00:00",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * @OA\Property(
     *      title="updated_at",
     *      description="record modification date time",
     *      example="2024-01-01 00:00:00",
     * )
     *
     * @var string
     */
    private $updated_at;
}