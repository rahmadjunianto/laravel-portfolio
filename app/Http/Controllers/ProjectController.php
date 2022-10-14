<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $projects = ProjectResource::collection(Project::with('skill')->get());
        return Inertia::render('Projects/index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $skills = Skill::all();
       return Inertia::render('Projects/create',compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required','image'],
            'name' => ['required','min:3'],
            'skill_id' => ['required'],
        ]);

        if ($request->hasFile('image')){
            $image = $request->file('image')->store('projects');
            Project::create([
                'name' => $request->name,
                'skill_id' => $request->skill_id,
                'url' => $request->url,
                'image' => $image
            ]);
            return Redirect::route('projects.index')->with('message','Project created successfully');
        }
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Project $project
     * @return \Inertia\Response
     */
    public function edit(Project $project)
    {

        $skills = Skill::all();
        return Inertia::render("Projects/edit",compact('project','skills'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Project $project)
    {
        $image= $project->image;
        $request->validate([
            'name' => ['required','min:3']
        ]);

        if ($request->hasFile('image')){
            Storage::delete($project->image);
            $image = $request->file('image')->store('projects');
        }
        $project->update([
            'image' =>$image,
            'name' =>$request->name,
            'skill_id' =>$request->skill_id,
            'url' =>$request->url,
        ]);
        return Redirect::route('projects.index')->with('message','Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Project $project)
    {
        Storage::delete($project->image);
        $project->delete();
        return Redirect::route('projects.index')->with('message','Project deleted successfully');
    }
}
