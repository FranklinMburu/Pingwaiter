@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Add Menu Item</h1>
    <form method="POST" action="{{ route('admin.menu.storeItem', $category) }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full" required>
            @error('name')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" class="textarea textarea-bordered w-full">{{ old('description') }}</textarea>
            @error('description')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">Price</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="input input-bordered w-full" required>
            @error('price')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div x-data="{ imageUrl: null }" x-init="imageUrl = null">
            <label class="block font-semibold mb-1">Image</label>
            <input type="file" name="image" class="file-input file-input-bordered w-full" @change="
                const file = $event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => imageUrl = e.target.result;
                    reader.readAsDataURL(file);
                } else {
                    imageUrl = null;
                }
            ">
            <template x-if="imageUrl">
                <img :src="imageUrl" class="w-24 h-24 object-cover rounded mt-2 border" alt="Preview">
            </template>
            @error('image')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex items-center gap-2">
            <label class="font-semibold">Available</label>
            <input type="checkbox" name="is_available" value="1" class="toggle" {{ old('is_available', true) ? 'checked' : '' }}>
        </div>
        <div>
            <label class="block font-semibold mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="input input-bordered w-full">
            @error('sort_order')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
