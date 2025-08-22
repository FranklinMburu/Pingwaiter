@extends('layouts.main')
@section('content')
<div class="max-w-lg mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">{{ isset($item) ? 'Edit Item' : 'Add Item' }}</h1>
    <form method="POST" action="{{ isset($item) ? route('menu.item.update', [$category, $item]) : route('menu.item.store', $category) }}" enctype="multipart/form-data">
        @csrf
        @if(isset($item)) @method('PUT') @endif
        <div class="mb-4">
            <label class="block font-medium mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $item->name ?? '') }}" required />
            @error('name') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description', $item->description ?? '') }}</textarea>
            @error('description') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Price</label>
            <input type="number" name="price" class="w-full border rounded px-3 py-2" value="{{ old('price', $item->price ?? '') }}" min="0" step="0.01" required />
            @error('price') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Image</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2" accept="image/*" />
            @if(isset($item) && $item->image)
                <img src="{{ asset('storage/'.$item->image) }}" class="h-16 w-16 mt-2 rounded object-cover border" alt="{{ $item->name }}" />
            @endif
            @error('image') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4 flex items-center gap-2">
            <label class="font-medium">Available</label>
            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $item->is_available ?? true) ? 'checked' : '' }} />
        </div>
        <div class="mb-4">
            <label class="block font-medium mb-1">Sort Order</label>
            <input type="number" name="sort_order" class="w-full border rounded px-3 py-2" value="{{ old('sort_order', $item->sort_order ?? 0) }}" min="0" />
            @error('sort_order') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('menu.index') }}" class="px-4 py-2 rounded border">Cancel</a>
        </div>
    </form>
</div>
@endsection
