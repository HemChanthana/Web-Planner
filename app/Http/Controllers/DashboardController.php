<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // User hashtags
        $hashtags = Hashtag::where('user_id', $userId)
                           ->orderBy('name')
                           ->get();

        // All tasks (if you still need them somewhere)
        $tasks = Task::where('user_id', $userId)
                     ->orderBy('created_at', 'desc')
                     ->get();

        // Recent 6 tasks
        $recentTasks = Task::where('user_id', $userId)
                           ->latest()
                           ->take(6)
                           ->get();

        // User stats
        $myTasks = Task::where('user_id', $userId)->count();
        $myCompletedTasks = Task::where('user_id', $userId)
                                ->where('status', 'completed')
                                ->count();

        return view('dashboard', compact(
            'hashtags',
            'tasks',
            'recentTasks',
            'myTasks',
            'myCompletedTasks'
        ));
    }
}
