@props(['role'])



<div class="p-6 lg:p-8 bg-white dark:bg-gray-800">
    <!-- Your existing code for header and welcome message -->

    <!-- Grid starts here -->
    <div class="grid grid-cols-2 gap-6 mt-8">
        <!-- First item -->
        <a href="{{ route('admin.users.index') }}" class="flowbite-card">
            <div class="flex items-center p-4 bg-green-600 dark:bg-blue-300 rounded-lg">
                <!-- Your SVG or Flowbite icon here -->
                <div class="ml-4">
                    <div class="text-xl font-semibold @if (config('app.dark_mode')) text-white @endif">
                        <i class="fas fa-users"></i>
                        All Users
                    </div>
                    <p class="mt-1 text-sm @if (config('app.dark_mode')) text-gray-300 @endif">View all users.</p>
                </div>
            </div>
        </a>

        <!-- Second item -->
        <a href="{{ route('schools_admin.schools.index') }}" class="flowbite-card">
            <div class="flex items-center p-4 bg-green-600 dark:bg-blue-300 rounded-lg">
                <!-- Your SVG or Flowbite icon here -->
                <div class="ml-4">
                    <div class="text-xl font-semibold @if (config('app.dark_mode')) text-white @endif">
                        <i class="fas fa-school"></i>
                        Schools
                    </div>
                    <p class="mt-1 text-sm @if (config('app.dark_mode')) text-gray-300 @endif">View all schools.</p>
                </div>
            </div>
        </a>
        <!-- Third item -->
        <a href="{{ route('admin.users.index') }}" class="flowbite-card">
            <div class="flex items-center p-4 bg-green-600 dark:bg-blue-300 rounded-lg">
                <!-- Your SVG or Flowbite icon here -->
                <div class="ml-4">
                    <div class="text-xl font-semibold @if (config('app.dark_mode')) text-white @endif">
                        <i class="fas fa-users"></i>
                        All Users
                    </div>
                    <p class="mt-1 text-sm @if (config('app.dark_mode')) text-gray-300 @endif">View all users.</p>
                </div>
            </div>
        </a>
        <!-- Repeat the structure above for any additional items -->
    </div>
</div>