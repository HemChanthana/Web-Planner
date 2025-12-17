<x-app-layout class="font-albert">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome Back, {{ Auth::user()->name }}
            </h2>
            <span class="text-xl">📖</span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2">
        <div x-data="{ open: false }">

            <!-- Create Task Button -->
            <button 
                @click="open = true"
                class="flex gap-2 items-center px-5 py-3 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">
                Create Task
                <svg class="w-5 h-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-width="2" d="M5 12h14m-7 7V5"/>
                </svg>
            </button>


                <!-- display tasks -->
         <div class="mt-6">
    <h3 class="text-lg font-semibold mb-3">Recent Tasks</h3>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        
        @foreach ($recentTasks as $task)


        <!-- Task Card Component -->
        <div class="p-4 bg-blue-200 border rounded-xl shadow-sm ">

            

            
<div class="mb-2 flex items-center gap-3"
     x-data="{ completed: {{ $task->status === 'done' ? 'true' : 'false' }} }">

    <!-- Checkbox of each modals  -->
    <button 

    @click="
        completed = !completed;
        fetch('{{ route('tasks.toggle', $task->id) }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
    "
        class="w-6 h-6 flex items-center justify-center rounded-full border transition"
        :class="completed 
            ? 'bg-blue-600 border-blue-600' 
            : 'border-gray-400'"
    >
        <svg x-show="completed" xmlns="http://www.w3.org/2000/svg" 
             class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
    </button>

    <!-- Title of modals  -->
    <div  >
        <label class="text-gray-400">Title</label>
        <h4 class="font-semibold text-gray-900"
        :class="completed ? 'line-through text-gray-500' : ''"
        
        >{{ $task->title }}</h4>
    </div>

   

</div>


<label class="text-gray-400"> Description </label>
            <p class="text-gray-900 font-semibold text-sm mt-1">
                {{ Str::limit($task->description, 100) }}
            </p>
          <div class="mt-2 flex gap-7 flex-row items-center">
                <p class="text-gray-900 text-sm  mtt-1 font-light ">
                <span class="bg-yellow-200"> 
                    {{ '#' . $hashtags->whereIn('id', $task->hashtags->pluck('id'))->pluck('name')->join(',#') }}
                </span>
                </p>
               <a href="{{ route("tasks.index") }}"> <button class="bg-sky-600 text-sm text-white rounded-lg  hover:bg-sky-700 transition">View Details</button></a>
          </div>


           <div class="">
        <button type="button" class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none" data-dismiss-target="#toast-default" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
    </button>
    </div>


    <button
  class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700"
>
  Flowbite Test Button
</button>

<div id="toast-simple" class="flex items-center w-full max-w-sm p-4 text-body bg-neutral-primary-soft rounded-base shadow-xs border border-default" role="alert">
    <svg class="w-5 h-5 text-fg-brand" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 18-7 3 7-18 7 18-7-3Zm0 0v-5"/></svg>
    <div class="ms-2.5 text-sm border-s border-default ps-3.5">Message sent successfully.</div>
    <button type="button" class="ms-auto flex items-center justify-center text-body hover:text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded text-sm h-8 w-8 focus:outline-none" data-dismiss-target="#toast-simple" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
    </button>
</div>


<button
  data-dropdown-toggle="dropdown"
  class="bg-blue-600 text-white px-4 py-2 rounded"
>
  Dropdown
</button>

<div id="dropdown" class="hidden bg-white rounded shadow w-44">
  <ul class="py-2 text-sm">
    <li><a class="block px-4 py-2 hover:bg-gray-100">Item</a></li>
  </ul>
</div>


            
        </div>


        
        @endforeach

        @if ($recentTasks->isEmpty())
            <p class="text-gray-500 italic">No tasks yet.</p>
        @endif

    </div>
</div>






            <!-- Modal for create tasks -->
            <div 
                x-show="open"
                x-transition.opacity
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
            >
                <div 
                    @click.outside="open = false"
                    class="bg-white p-6 rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto"
                >
                    <h2 class="text-xl font-semibold mb-4">Create New Task</h2>

                    <!-- FORM -->
                    <form action="{{ route('tasks.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-4">
                            <label class="block font-semibold">Title</label>
                            <input type="text" name="title" class="w-full p-3 border rounded-lg" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block font-semibold">Description</label>
                            <textarea name="description" class="w-full p-3 border rounded-lg" rows="3"></textarea>
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label class="block font-semibold">Priority</label>
                            <select name="priority" class="w-full p-3 border rounded-lg">
                                <option value="Low">Low</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <!-- End Date -->
                        <div class="mb-4">
                            <label class="block font-semibold">End Date</label>
                            <input type="date" name="end_date" class="w-full p-3 border rounded-lg">
                        </div>

                        <!-- Hashtags -->
                        <div class="mb-4">
                            <label class="block font-semibold">Hashtags</label>
                            <select name="hashtags[]" class="w-full p-3 border rounded-lg" multiple>
                                @foreach ($hashtags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- New Hashtag -->
                        <div class="mb-4">
                            <input type="text" name="new_hashtag" placeholder="Add new hashtag (optional)" class="w-full p-3 border rounded-lg">
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label class="block font-semibold">Comment</label>
                            
                            <textarea name="comment" class="w-full p-3 border rounded-lg" rows="2"></textarea>
                           
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="block font-semibold">Upload File</label>
                            <input 
                                id="filepond"
                                name="file"
                                type="file"
                                class="filepond"
                            >
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                            <button data-popover-target="popover-offset" data-popover-offset="30" class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700">
                                Save Task
                            </button>




                          </div>
                           

                        </div>

                    </form>

                </div>
            </div>
        </div>


                            @if (session('success'))
<div class="absolute top-5 right-5 bg-white border p-3 rounded-lg shadow">
    {{ session('success') }}
</div>
@endif

    </div>

    @push('scripts')
    <script>
    document.addEventListener("DOMContentLoaded", () => {

        FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize
        );

      FilePond.create(document.querySelector('#filepond'), {
    allowMultiple: false,
    maxFileSize: '20mb',
  acceptedFileTypes: [
    'image/*',
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/msword'
],


    // REQUIRED: make it submit actual file 
    storeAsFile: true,    

    // No AJAX
    server: null
});

    });
    </script>
    @endpush

    <script>
    setTimeout(() => {
        const successAlert = document.querySelector('.absolute.top-5.right-5');
        if (successAlert) {
            successAlert.remove();
        }
    }, 3000);
    </script>



    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>



</x-app-layout>
