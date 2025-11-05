@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Edit School</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('schools.update', $school) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div><label class="block mb-2">School Name *</label><input type="text" name="name" value="{{ old('name', $school->name) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2">Address *</label><textarea name="address" rows="3" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required>{{ old('address', $school->address) }}</textarea></div>
            <div><label class="block mb-2">Phone Number *</label><input type="text" name="phone_number" value="{{ old('phone_number', $school->phone_number) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2">Email *</label><input type="email" name="school_email" value="{{ old('school_email', $school->school_email) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2">Website *</label><input type="url" name="school_website" value="{{ old('school_website', $school->school_website) }}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div class="flex gap-4"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update School</button><a href="{{ route('schools.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
