<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Profile Information</h2>

        <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <div class="w-24 h-24 rounded-full overflow-hidden shadow-lg">
                    @if($parent->picture_path)
                    <img src="{{ Storage::disk('s3')->url($parent->picture_path) }}" alt="Current Picture" class="mt-2 w-24 h-24 rounded-lg">
                    @endif

                </div>
                <div class="ml-6">
                    <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mb-6">
                <h4 class="text-lg font-semibold">Contact Information</h4>
                <p class="text-gray-700">
                    <strong>Phone:</strong> {{ $parent->phone_number }}
                </p>
            </div>

            <div class="mb-6">
                <h4 class="text-lg font-semibold">Race</h4>
                <p class="text-gray-700">{{ $parent->race ?? 'Not specified' }}</p>
            </div>

            <div class="flex justify-end">
                <a
                    href="{{ route('parents.edit', $parent->id) }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</x-app-layout>