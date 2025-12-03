<?php

namespace App\Http\Controllers;
use App\Models\Hashtag;
use App\Models\Task;
use App\Models\User;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        $hashtags = Hashtag::where('user_id', auth()->id())->orderBy('name')->get();

        // you may also preload tasks
        $tasks = Task::where('user_id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('dashboard', compact('hashtags', 'tasks'));
    }


}
