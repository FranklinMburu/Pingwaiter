@extends('layouts.main')
@section('content')
<div class="max-w-lg mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">{{ isset($category) ? 'Edit Category' : 'Add Category' }}</h1>
    <form method="POST" action="{{ isset($category) ? route('menu.category.update', $category) : route('menu.category.store') }}">
        @csrf
        @if(isset($category)) @method('PUT') @endif
        <div class="mb-4">
            <label class="block font-medium mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $category->name ?? '') }}" required />
            @error('name') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $category->description ?? '') }}</textarea>
            @error('description') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Sort Order</label>
            <input type="number" name="sort_order" class="w-full border rounded px-3 py-2" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0" />
            @error('sort_order') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4 flex items-center gap-2">
            <label class="font-medium">Active</label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} />
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('menu.index') }}" class="px-4 py-2 rounded border">Cancel</a>
        </div>
    </form>
</div>
@endsection
