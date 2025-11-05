@extends('layouts.app-extended')
@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Edit Class</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('classes.update', $class) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')
            <div><label class="block mb-2">Class Name *</label><input type="text" name="class_name" value="{{$class->class_name}}" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required></div>
            <div><label class="block mb-2">School *</label><select name="school_id" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required>@foreach($schools as $school)<option value="{{$school->id}}" {{$class->school_id==$school->id?'selected':''}}>{{$school->name}}</option>@endforeach</select></div>
            <div><label class="block mb-2">Child *</label><select name="child_id" class="bg-gray-50 border text-sm rounded-lg block w-full p-2.5" required>@foreach($childs as $child)<option value="{{$child->id}}" {{$class->child_id==$child->id?'selected':''}}>{{$child->child_name}}</option>@endforeach</select></div>
            <div class="flex gap-4"><button type="submit" class="text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">Update</button><a href="{{ route('classes.index') }}" class="py-2.5 px-5 text-sm bg-white border rounded-lg">Cancel</a></div>
        </form>
    </div>
</div>
@endsection
