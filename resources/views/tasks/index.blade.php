<x-app-layout>
    <x-slot name="header">

        <div class="flex justify-center gap-4 items-center">
              <h2 class="text-xl font-semibold">All Tasks</h2>
        <form action="{{ route('tasks.search') }}" method="GET">
        <input type="text" name="search" placeholder="Search Tasks"
               value="{{ $search ?? '' }}">

      <button type="submit" 
    class="text-body border border-neutral-500 hover:bg-neutral-secondary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary-soft shadow-xs font-medium leading-5     rounded-base text-sm px-4 py-2.5 focus:outline-none"> Search</button>
      
    </form>

<div class="inline-flex rounded-2xl overflow-hidden border shadow-sm" role="group">
  
<a href="{{ route('tasks.index', ['sort' => 'day']) }}">
      <button 
        type="button" 
        class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">
        Day
      </button>
</a>

 <a href="{{ route('tasks.index', ['sort' => 'week']) }}">
      <button 
        type="button" 
        class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 border-l border-blue-400 focus:ring-2 focus:ring-blue-300">
        Month
      </button>
 </a>

<a href="{{ route('tasks.index', ['sort' => 'year']) }}">
      <button 
        type="button" 
        class="px-4 py-2 text-sm font-medium bg-blue-500 text-white hover:bg-blue-600 border-l border-blue-400 focus:ring-2 focus:ring-blue-300">
        Year
      </button>
</a>

</div>



     </div>
    </x-slot>




    

    <div class="flex justify-center w-full mt-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 
                    gap-4 place-items-center max-w-6xl">

            {{-- SEARCH RESULTS --}}
            @if(!empty($results))
                @if(count($results) > 0)
                    @foreach ($results as $result)
                        <x-task-components.task-card :task="$result" :hashtags="$hashtags" />
                    @endforeach
                @else
                    <p>No results found.</p>
                @endif
            @endif


            @if(!empty($tasks))
                @foreach ($tasks as $task)
                    <x-task-components.task-card :task="$task" :hashtags="$hashtags" />
                @endforeach
            @endif


            

        </div>
    </div>

</x-app-layout>
