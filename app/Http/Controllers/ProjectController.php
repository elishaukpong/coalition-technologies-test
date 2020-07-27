<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('projects.index', ['projects' => Project::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = ['name' => 'required'];
        $errorMessages = ['name.required' => 'Provide a project name!'];

        $this->validate($request, $rules, $errorMessages);

        $project = Project::create([
            'name' => $request->name
        ]);

        if(! $project){
            return redirect()->back()->with('error','Cannot create project!')->withInput();
        }

        return redirect()->route('projects.index')->with('success','Project created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return  \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project)
    {
        return view('projects.show',['tasks' => $project->tasks()->oldest('position')->get(), 'project' => $project]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return Project | \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        if(! $project->delete()){
            return response()->json(['data' => 'Error'], 400);
        }

        return $project;
    }
}
