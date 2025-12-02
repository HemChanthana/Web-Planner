<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //






    public  function index()
    {

        $task = Task::get();
        return view('tasks.index',Compact('tasks'));
    }


    public function store(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }   



}
