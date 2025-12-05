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

            <!-- Modal -->
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


    // REQUIRED: Make FilePond submit the actual file in the form
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

</x-app-layout>
