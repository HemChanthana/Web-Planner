<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function index()
    {
        // monthly tasks: count tasks per month for the current year
        $monthlyTasks = [];
        $year = now()->year;

        for ($m = 1; $m <= 12; $m++) {
            $monthlyTasks[] = Task::whereYear('created_at', $year)
                                  ->whereMonth('created_at', $m)
                                  ->count();
        }

        return view('components.admin.index', [
            'totalUsers' => User::count(),
            'totalTasks' => Task::count(),
            'completedTasks' => Task::where('status', 'completed')->count(),
            'pendingTasks' => Task::where('status', 'pending')->count(),
            'recentTasks' => Task::latest()->take(5)->get(),
            'monthlyTasks' => $monthlyTasks,  
        ]);
    }

    public function viewAllUser(Request $request)
    {
        $users = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $users->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $users->paginate(10);
        return view('components.admin.user-controll', compact('users'));
    }



    // public function editUser($id)
    // {
    //     $user = User::findOrFail($id);
    //     return view('components.admin.edit-user', compact('user'));
    // }   


    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }   

    public function ViewAllTask()
    {
         $tasks = Task::query();

        $tasks = $tasks->paginate(6);
        return view('components.admin.task-controll', compact('tasks'));
    }
}
