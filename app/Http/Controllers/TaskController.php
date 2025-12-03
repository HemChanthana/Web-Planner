<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Hashtag;
use App\Models\Comment;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::get();
        return view('tasks.index', compact('task'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|max:255',
            'description'  => 'nullable|string',
            'priority'     => 'required|in:Low,Normal,High',
            'end_date'     => 'nullable|date',

            'hashtags'     => 'nullable|array',
            'hashtags.*'   => 'integer',

            'new_hashtag'  => 'nullable|string|max:50',
            'comment'      => 'nullable|string|max:500',

            'file' => 'nullable|file|mimes:jpg,png,pdf,docx|max:20480',
        ]);

        // Create Task
        $task = Task::create([
            'user_id'    => auth()->id(),
            'parent_id'  => null,
            'title'      => $request->title,
            'description'=> $request->description,
            'priority'   => $request->priority,
            'status'     => 'pending',
            'end_date'   => $request->end_date,
        ]);

        // Attach existing hashtags
        if ($request->hashtags) {
            $task->hashtags()->sync($request->hashtags);
        }

        // Create new hashtag
        if ($request->new_hashtag) {
            $hashtag = Hashtag::firstOrCreate([
                'user_id' => auth()->id(),
                'name'    => $request->new_hashtag,
            ]);
            $task->hashtags()->attach($hashtag->id);
        }

        // Add comment
        if ($request->comment) {
            $task->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment
            ]);
        }

        // Handle File Upload (NORMAL FILE UPLOAD)
        if ($request->hasFile('file')) {

            $path = $request->file('file')->store('task_files', 'public');

            $task->files()->create([
                'file_path' => $path
            ]);
        }

        return back()->with('success', 'Task created successfully!');
    }
}
