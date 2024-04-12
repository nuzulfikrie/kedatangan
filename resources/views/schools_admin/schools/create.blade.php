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
      <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
          <h1 class="text-black dark:text-white">Create School</h1>
          <form action="{{ route('schools_admin.schools.store') }}" method="POST">
              @csrf
              <!-- School Name -->
              <div class="mb-6">
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School Name:</label>
                  <input type="text" name="name" id="name" placeholder="Enter school name" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                  @error('name')
                      <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                  @enderror
              </div>

              <!-- Address -->
              <div class="mb-6">
                  <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address:</label>
                  <input type="text" name="address" id="address" placeholder="1234 Main St" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('address') border-red-500 @enderror" value="{{ old('address') }}">
                  @error('address')
                      <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                  @enderror
              </div>

              <!-- Phone Number -->
              <div class="mb-6">
                  <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone Number:</label>
                  <input type="text" name="phone_number" id="phone_number" placeholder="123-456-7890" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('phone_number') border-red-500 @enderror" value="{{ old('phone_number') }}">
                  @error('phone_number')
                      <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                  @enderror
              </div>

              <!-- School Email -->
              <div class="mb-6">
                  <label for="school_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School Email:</label>
                  <input type="email" name="school_email" id="school_email" placeholder="school@example.com" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('school_email') border-red-500 @enderror" value="{{ old('school_email') }}">
                  @error('school_email')
                      <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                  @enderror
              </div>

              <!-- School Website -->
              <div class="mb-6">
                  <label for="school_website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">School Website:</label>
                  <input type="text" name="school_website" id="school_website" placeholder="www.example.com" class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('school_website') border-red-500 @enderror" value="{{ old('school_website') }}">
                  @error('school_website')
                      <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                  @enderror
              </div>

              <!-- Form Buttons -->
              <div class="flex justify-end gap-4">
                  <button type="reset" class="text-gray-600 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Reset</button>
                  <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create</button>
              </div>
              <input type="hidden" name="schools_admin._id" value="{{ Auth::user()->id }}">
          </form>
      </div>
  </x-app-layout>
