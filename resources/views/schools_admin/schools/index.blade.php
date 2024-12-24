<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <x-school-admins-table-list
                :schools="$schools"
                :query="$query ?? ''"
                :filter="$filter ?? 'all'" />
        </div>
    </div>
</x-app-layout>