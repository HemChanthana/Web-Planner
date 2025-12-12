<x-app-layout>
    <x-slot name="header">

        <div class="flex justify-center gap-4 items-center">

            <h2 class="text-xl font-semibold">All Tasks</h2>

            {{-- SEARCH BAR --}}
            <form action="{{ route('tasks.search') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search Tasks"
                    value="{{ $search ?? '' }}"
                    class="border rounded px-2 py-1"
                >

                <button 
                    type="submit"
                    class="text-body border border-neutral-500 hover:bg-neutral-secondary-medium 
                    hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft 
                    shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5">
                    Search
                </button>
            </form>

            {{-- SORT BUTTONS --}}
            <div class="inline-flex rounded-2xl overflow-hidden border shadow-sm">

                <a href="{{ route('tasks.index', ['sort' => 'day']) }}">
                    <button class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600">
                        Day
                    </button>
                </a>

                <a href="{{ route('tasks.index', ['sort' => 'month']) }}">
                    <button class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 border-l">
                        Month
                    </button>
                </a>

                <a href="{{ route('tasks.index', ['sort' => 'year']) }}">
                    <button class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 border-l">
                        Year
                    </button>
                </a>

            </div>
        </div>

        {{-- PAGINATION TOP --}}
        <div class="flex justify-center w-full mt-6">
            {{ $tasks->links() }}
        </div>

    </x-slot>

    {{-- MAIN TASK GRID --}}
    <div class="flex justify-center w-full mt-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 
            gap-6 w-full max-w-7xl mx-auto px-4">

            {{-- SEARCH RESULTS --}}
            @if($results)
                @if($results->count() > 0)
                    @foreach ($results as $task)
                        <x-task-components.task-card 
                            :task="$task" 
                            :hashtags="$hashtags" />
                    @endforeach
                @else
                    <p>No results found.</p>
                @endif

            {{-- DEFAULT PAGINATED TASKS --}}
            @else
                @foreach ($tasks as $task)
                    <x-task-components.task-card 
                        :task="$task" 
                        :hashtags="$hashtags" />
                @endforeach
            @endif

        </div>
    </div>

    {{-- PAGINATION BOTTOM --}}
    <div class="flex justify-center w-full mt-6">
        {{ $tasks->links() }}
    </div>

</x-app-layout>
