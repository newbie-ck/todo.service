<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use App\Http\Requests\TodoListRequest;
use App\Http\Requests\TodoCreateRequest;
use App\Http\Requests\TodoUpdateRequest;
use App\Exceptions\NotFoundTodoException;
use App\Helpers\Filterers\TodoFilterer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TodoUpdateStatusRequest;

class TodoController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/todos",
     *      tags={"Todo"},
     *      summary="To get all todos",
     *      @OA\Parameter(
     *          name="todo_title",
     *          in="query",
     *          description="todo's title",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="is_completed",
     *          in="query",
     *          description="indicator of the todo's completeness (true - completed, false - non-complete)",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="sortBy",
     *          in="query",
     *          description="based on attribute that return",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="orderBy",
     *          in="query",
     *          description="asc / desc",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="server error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/TodoResource"),
     *              ),
     *          ),
     *       ),
     * )
     */
    public function index(TodoListRequest $request)
    {
        $query = Todo::query();
        $todos = TodoFilterer::apply($request, $query);

        return $this->responseSuccess(TodoResource::collection($todos), 200);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/todos/{uuid}",
     *      tags={"Todo"},
     *      summary="To get a todo by uuid",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="todo's uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Todo not found.",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="server error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/TodoResource",
     *              ),
     *          ),
     *       ),
     * )
     */
    public function show(Request $request, $uuid)
    {
        $request->merge(['uuid' => $uuid]);

        Validator::make($request->all(), [
            'uuid' => ['required', 'string', 'max: 36', 'alpha_dash'],
        ])->validate();

        $todo = Todo::where('uuid', $uuid)->first();
        throw_if(!$todo, new NotFoundTodoException("Todo not found"));

        return $this->responseSuccess(new TodoResource($todo));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/todos",
     *      tags={"Todo"},
     *      summary="To create a new todo",
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/TodoCreate"),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="server error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/TodoResource",
     *              ),
     *          ),
     *       ),
     * )
     */
    public function store(TodoCreateRequest $request)
    {
        $todo = new Todo();
        $todo->uuid = Str::uuid();
        $todo->todo_title = $request->input('todo_title');
        $todo->save();

        return $this->responseSuccess(new TodoResource($todo), 201);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/todos/{uuid}",
     *      tags={"Todo"},
     *      summary="To update a todo",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="todo's uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/TodoUpdate"),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Todo not found.",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="server error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/TodoResource",
     *              ),
     *          ),
     *       ),
     * )
     */
    public function update(TodoUpdateRequest $request, $uuid)
    {
        $request->merge(['uuid' => $uuid]);

        Validator::make($request->all(), [
            'uuid' => ['required', 'string', 'max: 36', 'alpha_dash'],
        ])->validate();

        $todo = Todo::where('uuid', $uuid)->first();
        throw_if(!$todo, new NotFoundTodoException("Todo not found"));

        $todo->todo_title = $request->input('todo_title');
        $todo->save();

        return $this->responseSuccess(new TodoResource($todo));
    }

    /**
     * @OA\Patch(
     *      path="/api/v1/todos/{uuid}/status",
     *      tags={"Todo"},
     *      summary="To update a todo's status",
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="todo's uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(ref="#/components/schemas/TodoUpdateStatus"),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="not found",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  example="Todo not found.",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="server error",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=false,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="error message",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true,
     *                  description="boolean value to determine request success or fail",
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/TodoResource",
     *              ),
     *          ),
     *       ),
     * )
     */
    public function updateStatus(TodoUpdateStatusRequest $request, $uuid)
    {
        $request->merge(['uuid' => $uuid]);

        Validator::make($request->all(), [
            'uuid' => ['required', 'string', 'max: 36', 'alpha_dash'],
        ])->validate();

        $todo = Todo::where('uuid', $uuid)->first();
        throw_if(!$todo, new NotFoundTodoException("Todo not found"));

        $todo->is_completed = $request->input('is_completed');
        $todo->save();

        return $this->responseSuccess(new TodoResource($todo));
    }

    public function destroy(Request $request, $uuid)
    {
        $request->merge(['uuid' => $uuid]);

        Validator::make($request->all(), [
            'uuid' => ['required', 'string', 'max: 36', 'alpha_dash'],
        ])->validate();
    }
}
