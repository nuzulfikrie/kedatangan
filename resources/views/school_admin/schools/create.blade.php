<?php
/*
CREATE TABLE `schools_institutions` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`address` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`phone_number` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`school_email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`school_website` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	`record_active` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=101
;

*/
$userName = Auth::user()->name;

?>
<x-app-layout>
      <div class="p-6 lg:p-8 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">

        <h1><p  class="text-black dark:text-white"> Create School </p></h1>
      <?php
      $userName = Auth::user()->name;
      ?>
            <div class="p-6 lg:p-8 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">

                  <form action="{{ route('school_admin.schools.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                              <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                              <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}">
                              @error('name')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        </div>

                        <div class="mb-4">
                              <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                              <input type="text" name="address" id="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('address') }}">
                              @error('address')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        </div>

                        <div class="mb-4">
                              <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                              <input type="text" name="phone_number" id="phone_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('phone_number') }}">
                              @error('phone_number')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        </div>

                        <div class="mb-4">
                              <label for="school_email" class="block text-gray-700 text-sm font-bold mb-2">School Email:</label>
                              <input type="email" name="school_email" id="school_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('school_email') }}">
                              @error('school_email')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        </div>

                        <div class="mb-4">
                              <label for="school_website" class="block text-gray-700 text-sm font-bold mb-2">School Website:</label>
                              <input type="text" name="school_website" id="school_website" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('school_website') }}">
                              @error('school_website')
                                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        </div>

                        <div class="mb-4">
                              <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
                              <button type="reset" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reset</button>
                        </div>

                        <input type="hidden" name="school_admin_id" id="school_admin_id" value="{{ Auth::user()->id }}">
                  </form>
            </div>
      </div>
</x-app-layout>