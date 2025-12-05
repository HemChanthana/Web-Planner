<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">All Tasks</h2>
    </x-slot>

  <div class="flex justify-center w-full mt-6">
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 
                gap-4 place-items-center max-w-6xl">
        @foreach ($tasks as $task)
            <x-task-components.task-card :task="$task" :hashtags="$hashtags" />
        @endforeach
    </div>
</div>

</x-app-layout>
