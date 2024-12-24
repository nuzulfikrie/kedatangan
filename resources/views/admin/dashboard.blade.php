<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

$role = Auth::user()->role;
?>


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
                @if ($role == 'super_admin')
                <x-admin-welcome />
                @endif

            </div>
        </div>
    </div>
</x-app-layout>