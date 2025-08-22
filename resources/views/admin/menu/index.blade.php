@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Menu Management</h1>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-1/3">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-semibold">Categories</h2>
                <a href="{{ route('admin.menu.createCategory') }}" class="btn btn-primary">Add Category</a>
            </div>
            <ul id="category-list" x-data="{drag: false}" class="space-y-2">
                @foreach($categories as $category)
                <li class="bg-white rounded shadow p-3 flex items-center justify-between" :class="{'opacity-50': drag}">
                    <div>
                        <span class="font-semibold">{{ $category->name }}</span>
                        <span class="ml-2 text-xs text-gray-500">({{ $category->menuItems->count() }} items)</span>
                        <span class="ml-2">
                            <input type="checkbox" class="toggle toggle-sm" disabled {{ $category->is_active ? 'checked' : '' }}>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.menu.editCategory', $category) }}" class="btn btn-xs btn-secondary">Edit</a>
                        <form action="{{ route('admin.menu.destroyCategory', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="w-full md:w-2/3">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl font-semibold">Menu Items</h2>
                <a href="{{ route('admin.menu.createItem', $categories->first()) }}" class="btn btn-primary">Add Item</a>
            </div>
            <div x-data="menuSort()">
                @foreach($categories as $category)
                <div class="mb-6">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        {{ $category->name }}
                        <span class="text-xs text-gray-400">({{ $category->menuItems->count() }} items)</span>
                    </h3>
                    <ul class="space-y-2" x-ref="itemList" data-category="{{ $category->id }}">
                        @foreach($category->menuItems as $item)
                        <li class="bg-white rounded shadow p-3 flex items-center justify-between" draggable="true">
                            <div class="flex items-center gap-3">
                                @if($item->image_url)
                                <img src="{{ $item->image_url }}" class="w-10 h-10 object-cover rounded" alt="{{ $item->name }}">
                                @endif
                                <div>
                                    <div class="font-semibold">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->formatted_price }} | <input type="checkbox" class="toggle toggle-xs" disabled {{ $item->is_available ? 'checked' : '' }}> Available</div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.menu.editItem', [$category, $item]) }}" class="btn btn-xs btn-secondary">Edit</a>
                                <form action="{{ route('admin.menu.destroyItem', [$category, $item]) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
function menuSort() {
    return {
        // Drag-and-drop logic can be implemented here with Alpine.js or a library
    }
}
</script>
@endsection
