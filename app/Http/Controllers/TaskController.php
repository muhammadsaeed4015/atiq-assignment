<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatedTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\SharedTaskList;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request){
        $tasks = Task::where('created_by', auth()->user()->id)->get()->toArray();
        return response()->json(['message' => 'Tasks ', 'data'=>$tasks]);
    }

    public function store(CreatedTaskRequest $request){
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->created_by = auth()->user()->id;
        $task->save();

        return response()->json(['message' => "sexfully creaetd", "data" => $task]);
    }

    public function update(UpdateTaskRequest $request, $taskId){
        $task = Task::find($taskId);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->save();

        return response()->json(['message' => "sexfully updated", "data" => $task]);
    }

    public function updateTaskStatus(Request $request, $taskId){
        $task = Task::find($taskId);
        $task->status = $request->status;
        $task->save();
        return response()->json(['message' => "sexfully updated", "data" => $task]);
    }

    //Create delete task by id pnly owned by the login user
    public function delete($taskId){
        $task = Task::find($taskId);
        if($task->created_by == auth()->user()->id){
            $task->delete();
            return response()->json(['message' => "sexfully deleted", "data" => $task]);
        }else{
            return response()->json(['message' => "you dont have permission to delete this task"],
            403);
        }
    }



    

}
