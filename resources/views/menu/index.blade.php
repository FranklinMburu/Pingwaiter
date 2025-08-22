@extends('layouts.main')
@section('content')
<div class="max-w-5xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Menu Management</h1>
    <div class="flex flex-col gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded shadow p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-lg">{{ $category->name }}</span>
                        <span class="ml-2 text-xs px-2 py-1 rounded bg-gray-100" x-data="{ active: @js($category->is_active) }">
                            <span x-text="active ? 'Active' : 'Inactive'"></span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('menu.category.edit', $category) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('menu.category.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="mt-2 text-gray-600">{{ $category->description }}</div>
                <div class="mt-4">
                    <a href="{{ route('menu.item.create', $category) }}" class="inline-block bg-green-600 text-white px-3 py-1 rounded text-sm">Add Item</a>
                </div>
                <div class="mt-4">
                    <ul class="divide-y">
                        @foreach($category->items as $item)
                            <li class="flex items-center justify-between py-2">
                                <div class="flex items-center gap-3">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" class="h-10 w-10 rounded object-cover border" alt="{{ $item->name }}" />
                                    @endif
                                    <div>
                                        <div class="font-medium">{{ $item->name }}</div>
                                        <div class="text-xs text-gray-500">${{ $item->formatted_price }}</div>
                                    </div>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="text-xs px-2 py-1 rounded bg-gray-100" x-data="{ available: @js($item->is_available) }">
                                        <span x-text="available ? 'Available' : 'Unavailable'"></span>
                                    </span>
                                    <a href="{{ route('menu.item.edit', [$category, $item]) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('menu.item.destroy', [$category, $item]) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8">
        <a href="{{ route('menu.category.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Add Category</a>
    </div>
</div>
@endsection
