<?php

namespace App\Http\Controllers;

use App\Tasks;
use Illuminate\Http\Request;

class TasksController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = ['name' => 'required', 'description' => 'required', 'project_id' => 'required'];
        $errorMessages = ['name.required' => 'Provide a task name!','description.required' => 'Provide a description name!'];

        $data = $this->validate($request, $rules, $errorMessages);

        Tasks::create($data);

        return redirect()->route('projects.show', $request->project_id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tasks $task)
    {
        if(! $task->update($request->except(['_token','_method']))){
            return response()->json(['data' => 'Error'], 403);
        }

        return redirect()->route('projects.show', $task->project_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tasks  $tasks
     * @return Tasks | \Illuminate\Http\JsonResponse
     */
    public function destroy($tasks)
    {
        if(! $task = Tasks::findOrFail($tasks)->delete()){
            return response()->json(['data' => 'Error'], 403);
        }

        return $tasks;
    }

    public function changePosition(Request $request)
    {
        $rules = ['id' => 'required', 'position' => 'required'];

        $data = $this->validate($request, $rules);

        $task =  Tasks::findOrFail($request->id);


        $task->update(['position' => $request->position]);

        return $task;

    }
}
