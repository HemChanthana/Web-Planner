<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Hashtag;
use App\Models\Comment;
use App\Models\TaskFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{



  
public function index(Request $request)
{

    $userId = Auth::id();

    $hashtags = Hashtag::where('user_id', $userId)->get();

    // Base query
    $query = Task::where('user_id', $userId);

    // Sorting filters
    if ($request->sort === 'day') {
        $query->whereDate('created_at', Carbon::today());
    }

    if ($request->sort === 'month') {
        $query->whereMonth('created_at', now()->month)
              ->whereYear('created_at', now()->year);
    }

    if ($request->sort === 'year') {
        $query->whereYear('created_at', now()->year);
    }

    // Clone before pagination
    $allTasks = (clone $query)->get();

    // Paginated tasks
    $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

    // Comments only for visible tasks
    $comments = Comment::whereIn('task_id', $tasks->pluck('id'))->get();

    return view('tasks.index', [
        'tasks'     => $tasks,
        'allTasks'  => $allTasks,
        'hashtags'  => $hashtags,
        'comments'  => $comments,
        'results'   => null,      // no search results yet
        'search'    => null
    ]);
}


public function toggleStatus(Task $task)
{
    $task->status = $task->status === 'done' ? 'pending' : 'done';
    $task->save();

    return response()->json(['status' => $task->status]);
}




public  function Destroy(Task $task){

    $user_id = Auth::id();

    if ($task->$user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }   else{
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
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
        'status'      => 'nullable|in:pending,in-progress,done',

        'hashtags'     => 'nullable|array',
        'hashtags.*'   => 'integer',

        'new_hashtag'  => 'nullable|string|max:50',
        'comment'      => 'nullable|string|max:500',

        'file'         => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
    ]);

    $task->update([
        'title'       => $request->title,
        'description' => $request->description,
        'status'      => $request->status,
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

    // === replace file cod e ===
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

    // Redirect back with success message and display it kl
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
public function search(Request $request)
{
    $search = $request->input('search');
    $userId = Auth::id();

    // Search results
    $results = Task::where('user_id', $userId)
                   ->where('title', 'like', "%{$search}%")
                   ->get();

    $hashtags = Hashtag::where('user_id', $userId)->get();

    // Keep pagination for main list
    $tasks = Task::where('user_id', $userId)
                 ->orderBy('created_at', 'desc')
                 ->paginate(10);

    return view('tasks.index', [
        'tasks'    => $tasks,
        'allTasks' => null,
        'hashtags' => $hashtags,
        'comments' => [],
        'results'  => $results,
        'search'   => $search,
    ]);
}



}
