@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Edit Category</h1>
    <form method="POST" action="{{ route('admin.menu.updateCategory', $category) }}" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf @method('PUT')
        <div>
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input input-bordered w-full" required>
            @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="textarea textarea-bordered w-full">{{ old('description', $category->description) }}</textarea>
            @error('description')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold">Active</label>
            <input type="checkbox" name="is_active" value="1" class="toggle" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
        </div>
        <div>
            <label class="block font-semibold mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="input input-bordered w-full">
            @error('sort_order')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
