<?php

namespace App\Http\Documentations\Requests;

/**
 * @OA\Schema(
 *      title="Todo Update Status",
 *      description="Request body for update todo's status",
 *      required={"is_completed"}
 * )
 */
class TodoUpdateStatus
{
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
}
