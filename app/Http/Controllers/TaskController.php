<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Hashtag;
use App\Models\Comment;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{





  


    public function index()
    {
          $tasks = Task::where('user_id', Auth::id())->get();
          
    $hashtags = Hashtag::where('user_id', Auth::id())->get();


    return view('tasks.index', compact('tasks',"hashtags"));
    }


    // public fucnction show(Request $request)
    // {
    //     $task = Task::find($request->id);
    //     return view('tasks.show', data: compact('task'));
    // }
 public function update(Request $request, Task $task)
{
    // Ensure user owns the task
    if ($task->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Validate request
    $request->validate([
        'title'        => 'required|max:255',
        'description'  => 'nullable|string',
        'priority'     => 'required|in:Low,Normal,High',
        'end_date'     => 'nullable|date',

        'hashtags'     => 'nullable|array',
        'hashtags.*'   => 'integer',

        'new_hashtag'  => 'nullable|string|max:50',
        'comment'      => 'nullable|string|max:500',

        'file'         => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
    ]);

    // Update task fields
    $task->update([
        'title'       => $request->title,
        'description' => $request->description,
        'priority'    => $request->priority,
        'end_date'    => $request->end_date,
    ]);

    // === HASHTAGS ===
    if ($request->hashtags) {
        $task->hashtags()->sync($request->hashtags);
    } else {
        $task->hashtags()->detach();
    }

    // Add new hashtag
    if ($request->new_hashtag) {
        $hashtag = Hashtag::firstOrCreate([
            'user_id' => Auth::id(),
            'name'    => $request->new_hashtag,
        ]);
        $task->hashtags()->attach($hashtag->id);
    }

    // === COMMENT ===
    if ($request->comment) {
        $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);
    }

    // === FILE REPLACEMENT ===
    if ($request->hasFile('file')) {

        // Delete old file(s)
        if ($task->files->count() > 0) {
            foreach ($task->files as $oldFile) {
                Storage::disk('public')->delete($oldFile->file_path);
                $oldFile->delete();
            }
        }

        // Save new file
        $path = $request->file('file')->store('task_files', 'public');
        $task->files()->create([
            'file_path' => $path,
        ]);
    }

    // Redirect back with success message
    return redirect()->back()->with('success', 'Task updated successfully!');
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


        if ($request->comment) {
            $task->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment
            ]);
        }


        if ($request->hasFile('file')) {

            $path = $request->file('file')->store('task_files', 'public');

            $task->files()->create([
                'file_path' => $path
            ]);
        }

        return back()->with('success',  'Task created successfully!');
    }
}
