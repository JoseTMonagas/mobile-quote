<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueForm;
use App\Models\Issue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Issues/Index', [
            'issues' => Issue::all()->map(function (Issue $issue) {
                    return [
                        'id' => $issue->id,
                        'name' => $issue->name,
                    ];
                }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Issues/CreateEdit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  IssueForm  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IssueForm $request)
    {
        $issue = Issue::create($request->validated());
        return response()->json($issue, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function edit(Issue $issue)
    {
        return Inertia::render('Issues/CreateEdit', [
            "issue" => $issue
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  IssueForm  $request
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(IssueForm $request, Issue $issue)
    {
        if($issue->update($request->validated())) {
            return response()->json($issue, 200);
        }
        return response()->json('ERROR', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        if($issue->delete()) {
            return response()->json('OK', 200);
        }
        return response()->json('ERROR', 500);
    }
}
