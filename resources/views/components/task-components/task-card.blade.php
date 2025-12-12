<div  x-data="{ open: false }" 
    class="bg-blue-100 p-4 rounded-2xl shadow-sm w-full h-full flex flex-col justify-between "
           >

    {{-- CARD PREVIEW --}}
    <h3 class="text-xl font-semibold mb-2">{{ $task->title }}</h3>
    <p class="text-gray-600 mb-4">{{ Str::limit($task->description, 120, '...') }}</p>

    <button @click="open = true"
            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
        Read More
    </button>







  <!-- MODAL -->    
    <div x-show="open"
         x-transition.opacity
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

        <div @click.outside="open = false"
             class="bg-white rounded-lg w-full max-w-lg p-6 shadow-lg max-h-[90vh] overflow-y-auto">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Edit Task</h2>
                <button @click="open = false" class="text-2xl font-bold text-gray-500">&times;</button>
            </div>

            {{-- FORM --}}
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- TITLE --}}
                <div class="mb-4">
                    <label class="font-semibold">Title</label>
                    <input type="text" name="title" value="{{ $task->title }}"
                           class="w-full border rounded p-2" required>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-4">
                    <label class="font-semibold">Description</label>
                    <textarea name="description" class="w-full border rounded p-2" rows="3">{{ $task->description }}</textarea>
                </div>

                <div class="mb-4">
    <label class="font-semibold">Status</label>
    <select name="status" class="w-full p-2 border rounded">
        <option value="pending"      {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="in-progress"  {{ $task->status === 'in-progress' ? 'selected' : '' }}>In Progress</option>
        <option value="done"         {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
    </select>
</div>




                {{-- PRIORITY --}}
                <div class="mb-4">
                    <label class="font-semibold">Priority</label>
                    <select name="priority" class="w-full p-2 border rounded">
                        <option value="Low"    {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                        <option value="Normal" {{ $task->priority == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="High"   {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                {{-- END DATE --}}
                <div class="mb-4">
                    <label class="font-semibold">End Date</label>
                    <input type="date" name="end_date" value="{{ $task->end_date }}"
                           class="w-full border rounded p-2">
                </div>

                {{-- HASHTAGS --}}
                <div class="mb-4">
                    <label class="font-semibold">Hashtags</label>
                    <select name="hashtags[]" class="w-full p-2 border rounded" multiple>
                        @foreach ($hashtags as $tag)
                            <option value="{{ $tag->id }}" 
                                {{ $task->hashtags->contains($tag->id) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- NEW HASHTAG --}}
                <input type="text" name="new_hashtag"
                       placeholder="Add new hashtag..."
                       class="w-full p-2 border rounded mb-4">

                {{-- COMMENT --}}
                <div class="mb-4">
                    <label class="font-semibold">Add Comment</label>
                  <textarea name="comment" rows="5" class="w-full p-2 border rounded">
@foreach ($task->comments as $c)
{{ $c->user->name }}: {{ $c->comment }} ({{ $c->created_at->diffForHumans() }})

@endforeach
</textarea>


                </div>

                {{-- FILE PREVIEW --}}
                @foreach ($task->files as $file)
                    @php
                        $path = asset('storage/' . $file->file_path);
                        $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                    @endphp

                    {{-- IMAGE --}}
                    @if (in_array($ext, ['jpg','jpeg','png']))
                        <img src="{{ $path }}" class="max-h-40 object-cover rounded mb-3 border">
                    @endif

                    {{-- PDF --}}
                    @if ($ext === 'pdf')
                        <embed src="{{ $path }}" class="w-full h-48 rounded border mb-3">
                    @endif

                    {{-- DOCX --}}
                    @if (in_array($ext, ['doc','docx']))
                        <a href="{{ $path }}" target="_blank"
                           class="text-blue-600 underline block mb-2">
                            Download Document ({{ strtoupper($ext) }})
                        </a>
                    @endif
                @endforeach

                {{-- UPLOAD NEW FILE --}}
                <div class="mb-4">
                    <label class="font-semibold">Replace File</label>
                    <input type="file" name="file" class="w-full p-2 border rounded">
                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button"
                            @click="open = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
