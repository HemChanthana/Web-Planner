<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="p-6 space-y-6">


<div class="grid grid-cols-2 md:grid-cols-4 gap-4">

 <a  href="{{ route('admin.users') }}">
        <div class="bg-white p-4 rounded-xl shadow-sm h-32 flex flex-col items-center justify-center">
            <p class="text-gray-500 text-sm">Users</p>
            <h2 class="text-2xl font-bold mt-1">{{ $totalUsers }}</h2>
        </div>
 </a>

 <a href="{{ route('admin.users.task') }}">
        <div class="bg-white p-4 rounded-xl shadow-sm h-32 flex flex-col items-center justify-center">
            <p class="text-gray-500 text-sm">Tasks</p>
            <h2 class="text-2xl font-bold mt-1">{{ $totalTasks }}</h2>
        </div>
    
 </a>

   <a>
        <div class="bg-white p-4 rounded-xl shadow-sm h-32 flex flex-col items-center justify-center">
            <p class="text-gray-500 text-sm">Completed</p>
            <h2 class="text-2xl font-bold text-green-600 mt-1">{{ $completedTasks }}</h2>
        </div>
   </a>

    <a>
        <div class="bg-white p-4 rounded-xl shadow-sm h-32 flex flex-col items-center justify-center">

        <p class="text-gray-500 text-sm">Pending</p>
        <h2 class="text-2xl font-bold text-yellow-600 mt-1">{{ $pendingTasks }}</h2>

    </div>
</a>


</div>



        {{-- CHART PLACEHOLDER --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold mb-3">Monthly Task Overview</h3>
            <div class="h-48 flex items-center justify-center text-gray-400">
        <canvas id="tasksChart" class="w-full h-48"></canvas>
            </div>
        </div>


        {{-- RECENT TASKS TABLE --}}
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h3 class="font-semibold mb-3">Recent Tasks</h3>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <th class="p-3">Task</th>
                        <th class="p-3">Assigned To</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTasks as $task)
                        <tr class="border-b">
                            <td class="p-3">{{ $task->title }}</td>
                            <td class="p-3">{{ $task->user->name ?? 'N/A' }}</td>
                            <td class="p-3">
                                @if($task->status === 'completed')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Completed</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span>
                                @endif
                            </td>
                            <td class="p-3">{{ $task->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <script>


  const ctx = document.getElementById('tasksChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Tasks',
                data: @json($monthlyTasks),
                backgroundColor: 'rgba(59, 130, 246, 0.5)',  // blue
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                borderRadius: 10,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
        </script>

    </div>

</x-app-layout>
