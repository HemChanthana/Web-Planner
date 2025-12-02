<x-app-layout>
    <x-slot name="header">
      <div class="flex items-center gap-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome Back, ') . Auth::user()->name }}
         
        </h2>

        <span class="text-xl"> &#128214;</span>
    </div>
    </x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a href="{{ route('tasks.index') }}" 
            class="inline-flex items-center text-white bg-sky-600  rounded-lg border-transparent shadow-xs
                   hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
                   font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Create Task
        </a>
    </div>


    <div x-data="{ open: false }">

    <!-- Create Task Button -->
    <button 
        @click="open = true"
        class="px-5 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
        Create Task
    </button>

    <!-- Modal Background -->
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
    >
        <!-- Modal Box -->
        <div 
            @click.away="open = false"
            class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg"
            x-transition
        >
            <h2 class="text-xl font-semibold mb-4">Create New Task</h2>

            <!-- Form -->
            <form >
                @csrf

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input 
                        type="text" 
                        name="title" 
                        class="mt-1 w-full p-3 border rounded-lg"
                        required
                    >
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea 
                        name="description" 
                        rows="3" 
                        class="mt-1 w-full p-3 border rounded-lg"
                    ></textarea>
                </div>


                <div class="mb-4">
                     <label class="block text-sm font-medium text-gray-700">Description</label> 
                    
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 mt-6">
                    <button 
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Cancel
                    </button>

                    <button 
                        class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                        Save Task
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>


</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
        </div>
    </div>
</x-app-layout>
