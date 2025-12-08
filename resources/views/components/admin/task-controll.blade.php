<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
        Task Management
        </h2>
    </x-slot>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 p-6 gap-6" 
     x-data="{ openView:false, selectedTask:{} }">

          
    @foreach ($tasks as $task)
    
    <div class="bg-neutral-primary-soft block max-w-sm p-6 border-2 border-default rounded-base shadow-md">
        
        <a href="#">
           
            <h5 class="mt-6 mb-2 text-2xl font-semibold tracking-tight text-heading">{{ $task->title }}</h5>
        </a>
        <p class="mb-6 text-body">{{ Str::Limit($task->description, limit:120, end:'...') }}</p>
      <button 
    type="button"
    class="inline-flex items-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-blue-400 hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
    @click="openView = true; selectedTask = {{ $task }}"
>
    View more
    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
</button>

        <!-- Additional Modal Info -->
    </div>
    
    @endforeach
    

    <!-- View More Modal -->
<div 
    x-show="openView"
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
>
    <div class="bg-white p-6 rounded-xl w-full max-w-lg shadow-lg">

        <h2 class="text-xl font-semibold mb-3" x-text="selectedTask.title"></h2>

        <p class="text-gray-600 mb-4">
            <span class="font-semibold">Created by:</span>
            <span x-text="selectedTask.user?.name ?? 'Unknown'"></span>
        </p>

        <p class="text-gray-700 mb-6" x-text="selectedTask.description"></p>

        <div class="flex justify-end gap-3">
            <button
                class="px-4 py-2 bg-gray-300 rounded"
                @click="openView = false"
            >
                Close
            </button>
        </div>

    </div>
</div>



    
    
        </div>


    <div class="flex justify-center w-full mt-6">
         {{ $tasks->links() }}
         </div>

    </div>

</x-app-layout>