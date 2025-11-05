@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4" x-data="{previewUrl:null}">
    <h1 class="text-3xl font-bold mb-6">Add New Child</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('childs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div><label class="block mb-2 text-sm font-medium">Child Name *</label><input type="text" name="child_name" value="{{ old('child_name') }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2 text-sm font-medium">Gender *</label><select name="child_gender" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required><option value="">Select gender</option><option value="Male">Male</option><option value="Female">Female</option></select></div>
            <div><label class="block mb-2 text-sm font-medium">Email *</label><input type="email" name="email" value="{{ old('email') }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2 text-sm font-medium">School *</label><select name="school_id" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required><option value="">Select school</option>@foreach($schools as $school)<option value="{{ $school->id }}">{{ $school->name }}</option>@endforeach</select></div>
            <div><label class="block mb-2 text-sm font-medium">Parents</label><select name="parents[]" multiple class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5">@foreach($parents as $parent)<option value="{{ $parent->id }}">{{ $parent->parent_name }}</option>@endforeach</select><p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple</p></div>
            <div><label class="block mb-2">Picture</label><input type="file" name="picture" accept="image/*" class="block w-full text-sm border rounded-lg" @change="const f=$event.target.files[0];if(f){const r=new FileReader();r.onload=e=>previewUrl=e.target.result;r.readAsDataURL(f)}"><img x-show="previewUrl" :src="previewUrl" class="mt-2 w-24 h-24 rounded-full"></div>
            <div class="flex gap-4"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Create Child</button><a href="{{ route('childs.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
