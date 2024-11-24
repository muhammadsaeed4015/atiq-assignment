<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTaskListRequest;
use App\Http\Requests\UpdateTaskListRequest;
use App\Http\Requests\CreatedSharedTaskListRequest;
use App\Models\SharedTaskList;
use App\Models\Task;
use App\Models\TaskList;


class TaskListController extends Controller
{
    public function index(Request $request){
        $tasks = TaskList::where('created_by', auth()->user()->id)
        ->with('tasks')
        ->get()->toArray();
        return response()->json(['message' => 'Task List ', 'data'=>$tasks]);
    }

    //create function to store task list also if request contain the task_ids also update the task_list_id in Task
    public function store(CreateTaskListRequest $request){
        $taskList = new TaskList();
        $taskList->title = $request->title;
        $taskList->description = $request->description;
        $taskList->created_by = auth()->user()->id;
        $taskList->save();
        if($request->task_ids){
            foreach($request->task_ids as $task_id){
                Task::where('id', $task_id)->update(['task_list_id' => $taskList->id]);
            }
        }
        return response()->json(['message' => 'Task List Created Successfully', 'data'=>$taskList]);
    }
    //create function to update task list also if request contain the task_ids also update the task_list_id in Task and remove the othee
    public function update(UpdateTaskListRequest $request, $taskListId){
        $taskList = TaskList::find($taskListId);
        $taskList->title = $request->title;
        $taskList->description = $request->description;
        $taskList->save();
        if($request->task_ids){
            Task::where('task_list_id', $taskListId)->update(['task_list_id' => null
            ]);
            foreach($request->task_ids as $task_id){
                Task::where('id', $task_id)->update(['task_list_id' => $taskListId]);
            }
        }
        return response()->json(['message' => 'Task List Updated Successfully', 'data'=>$taskList]);
    }

    //create function to update/add task list is in task
    public function addTaskToList(Request $request, $taskListId){
        $task = Task::find($request->task_id);
        $task->task_list_id = $taskListId;
        $task->save();
        return response()->json(['message' => 'Task Added To Task List Successfully', 'data'=>$task]);
    }

    public function removeTaskFromList(Request $request, $taskListId){
        $task = Task::find($request->task_id);
        $task->task_list_id = null;
        $task->save();
        return response()->json(['message' => 'Task Remove From Task List Successfully', 'data'=>$task]);
    }
    

    //Create delete task List  also remove id from task
    public function destroy($taskListId){
        $taskList = TaskList::find($taskListId);
        $taskList->delete();
        Task::where('task_list_id', $taskListId)->update(['task_list_id' => null]);
        return response()->json(['message' => 'Task List Deleted Successfully']);
    }



    
    
    public function getSharedTaskLists(Request $request){
        $tasks = SharedTaskList::where('shared_with', auth()->user()->id)->get()->toArray();
        return response()->json(['message' => 'Tasks ', 'data'=>$tasks]);
    }
    
    public function storeSharedTaskList(CreatedSharedTaskListRequest $request){
        $task = new SharedTaskList();
        $task->task_list_id = $request->task_list_id;
        $task->shared_with = $request->shared_with;
        $task->permission = $request->permission;
        $task->save();

        return response()->json(['message' => "sexfully shared", "data" => $task]);
    }

    //create function to udpate shared task premission 
    public function updateSharedTaskListPermission(Request $request, $sharedTaskId
    ){
        $sharedTaskList = SharedTaskList::find($sharedTaskId);
        $sharedTaskList->permission = $request->permission;
        $sharedTaskList->save();
        return response()->json(['message' => "sexfully updated", "data" => $sharedTaskList]);
    }

    //create function to remove shared task
    public function deleteSharedTaskList($sharedTaskId){
        $sharedTaskList = SharedTaskList::find($sharedTaskId);
        $sharedTaskList->delete();
        return response()->json(['message' => "sexfully deleted", "data" => $sharedTaskList]);
    }


}
