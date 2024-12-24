<x-app-layout>
    <div class="max-w-sm mx-auto mt-6">
        <div class="bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-center p-6">
                <img src="{{ Storage::disk('s3')->url($child->picture_path) }}" alt="{{ $child->child_name}}'s Avatar" class="w-24 h-24 rounded-full mb-4">
                <h5 class="mb-2 text-xl font-semibold text-gray-900 dark:text-white">{{ $child->child_name}}
                </h5>
                <p class="text-sm text-gray-500 dark:text-gray-400">Date of Birth: <span class="font-medium text-gray-900 dark:text-white">{{ $child->dob}}</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gender: <span class="font-medium text-gray-900 dark:text-white">{{ $child->gender}}</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Email: <span class="font-medium text-gray-900 dark:text-white">{{ $child->email}}</span></p>
                @if($child->parent)
                @foreach ($child->parent as $parent )
                <p class="text-sm text-gray-500 dark:text-gray-400">Parent:
                    <span class="font-medium text-gray-900 dark:text-white"><a href="{{ route('parents.profile',$parent->id)}}">{{ $parent->parent_name}}</a></span>
                </p>

                @endforeach
                @endif
                <p class="text-sm text-gray-500 dark:text-gray-400">School: <span class="font-medium text-gray-900 dark:text-white">{{ $child->school->name}}</span></p>

            </div>
            <div class="flex justify-center p-4">
                <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    View Profile
                </a>
            </div>
        </div>
    </div>
</x-app-layout>