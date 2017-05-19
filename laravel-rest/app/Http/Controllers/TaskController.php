<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use Carbon\Carbon;
use JWTAuth;
use App\Http\Requests;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function __construct() {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg','User not found'], 404);
        }

        $sort = $request->input('sort');

        switch ($sort) {
            case 'alphabet':
                $tasks = Task::where('user_id', $user->id)->orderBy('title','ASC')->get();
                break;
            
            case 'priority':
                $tasks = Task::where('user_id', $user->id)->orderBy('priority', 'ASC')->get();
                break;

            case 'created_at':
                $tasks = Task::where('user_id', $user->id)->orderBy('created_at','DESC')->get();
                break;

            default:
                $tasks = Task::where('user_id', $user->id)->get();
                break;
        }
        foreach ($tasks as $task) {
            $task->view_task = [
                'href' => 'api/v1/task/' . $task->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List of all tasks',
            'tasks' => $tasks
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100'
        ]);

        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg','User not found'], 404);
        }

        $title = $request->input('title');
        $due_date = $request->input('due_date');
        $due_time = $request->input('due_time');
        $note = $request->input('note');
        $subtasks = $request->input('subtasks');
        $priority = $request->input('priority');
        $priority = $priority == null ? 'normal' : $request->input('priority');
        $user_id = $user->id;

        $task = new Task([
            'title' => $title,
            'due_date' => Carbon::createFromFormat('Ymd', $due_date),
            'due_time' => Carbon::createFromFormat('Hie', $due_time),
            'note' => $note,
            'subtasks' => $subtasks,
            'priority' => $priority,
            'user_id' => $user_id
        ]);

        if($task->save()) {
            $task->view_task = [
                'href' => 'api/v1/task/' . $task->id,
                'method' => 'GET'
            ];

            $message = [
                'msg' => 'Task has been created',
                'task' => $task
            ];
            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error creating task',
            'task' => $task
        ];

        return response()->json($response, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg','User not found'], 404);
        }

        $task = Task::
        where('id', $id)
        ->where('user_id',$user->id)
        ->firstOrFail();
        $task->view_tasks = [
            'href' => 'api/v1/task',
            'method' => 'GET'
        ];

        $message = [
            'msg' => 'Task Information',
            'task' => $task
        ];
        return response()->json($message, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:100'
        ]);

        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg','User not found'], 404);
        }

        $title = $request->input('title');
        $due_date = $request->input('due_date');
        $due_time = $request->input('due_time');
        $note = $request->input('note');
        $subtasks = $request->input('subtasks');
        $priority = $request->input('priority');
        $user_id = $user->id;

        $task = Task::where('user_id',$user->id)->findOrFail($id);

        $task->title = $title;
        $task->due_date = Carbon::createFromFormat('Ymd', $due_date);
        $task->due_time = Carbon::createFromFormat('Hie', $due_time);
        $task->note = $note;
        $task->subtasks = $subtasks;
        $task->priority = $priority;

        if($task->update()) {
            $task->view_task = [
                'href' => 'api/v1/task/' . $task->id,
                'method' => 'GET'
            ];

            $message = [
                'msg' => 'Task has been updated',
                'task' => $task
            ];
            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Error updating task',
            'task' => $task
        ];

        return response()->json($response, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['msg','User not found'], 404);
        }

        $task = Task::
        where('id', $id)->firstOrFail()
        ->where('user_id', $user->id);
        if(!$task->delete()) {
            return response()->json(['msg' => 'deletion failed'], 404);
        }

        $response = [
            'msg' => 'Task has been deleted',
            'create' => [
                'href' => 'api/v1/task',
                'method' => 'POST',
                'params' => 'title, due_date, due_time, note, subtasks, priority'
            ]
        ];

        return response()->json($response, 200);
    }
}
