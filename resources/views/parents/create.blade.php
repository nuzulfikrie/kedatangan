@extends('layouts.app-extended')
@section('title', 'Add Parent')
@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ previewUrl: null }">
    <h1 class="text-3xl font-bold mb-6">Add New Parent</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('parents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block mb-2 text-sm font-medium">Parent Name <span class="text-red-500">*</span></label>
                <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                @error('parent_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Phone Number <span class="text-red-500">*</span></label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                @error('phone_number')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Associated User <span class="text-red-500">*</span></label>
                <select name="user_id" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5" required>
                    <option value="">Select a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Profile Picture</label>
                <input type="file" name="picture" accept="image/*" class="block w-full text-sm border rounded-lg cursor-pointer" @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = e => previewUrl = e.target.result; reader.readAsDataURL(file); }">
                <img x-show="previewUrl" :src="previewUrl" class="mt-2 w-24 h-24 rounded-full object-cover">
            </div>
            <div class="flex gap-4">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Create Parent</button>
                <a href="{{ route('parents.index') }}" class="py-2.5 px-5 text-sm font-medium bg-white border border-gray-200 rounded-lg hover:bg-gray-100">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
