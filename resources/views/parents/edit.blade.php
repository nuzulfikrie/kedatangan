@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4" x-data="{ previewUrl: '{{ $parent->picture_path ? Storage::url($parent->picture_path) : "" }}' }">
    <h1 class="text-3xl font-bold mb-6">Edit Parent</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('parents.update', $parent) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')
            <div><label class="block mb-2 text-sm font-medium">Parent Name *</label><input type="text" name="parent_name" value="{{ old('parent_name', $parent->parent_name) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2 text-sm font-medium">Phone *</label><input type="text" name="phone_number" value="{{ old('phone_number', $parent->phone_number) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2 text-sm font-medium">Email *</label><input type="email" name="email" value="{{ old('email', $parent->email) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2 text-sm font-medium">User *</label><select name="user_id" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required>@foreach($users as $user)<option value="{{ $user->id }}" {{ $parent->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>@endforeach</select></div>
            <div><label class="block mb-2">Picture</label><input type="file" name="picture" accept="image/*" class="block w-full text-sm border rounded-lg" @change="const f=$event.target.files[0];if(f){const r=new FileReader();r.onload=e=>previewUrl=e.target.result;r.readAsDataURL(f)}"><img x-show="previewUrl" :src="previewUrl" class="mt-2 w-24 h-24 rounded-full"></div>
            <div class="flex gap-4"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update</button><a href="{{ route('parents.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
