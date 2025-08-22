@extends('layouts.main')
@section('content')
<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-xl font-bold mb-4">Sort Menu Categories & Items</h1>
    <div x-data="{ categories: @js($categories) }">
        <ul id="category-list" class="space-y-2">
            <template x-for="category in categories" :key="category.id">
                <li class="bg-white rounded shadow p-2">
                    <div class="flex items-center justify-between">
                        <span x-text="category.name" class="font-semibold"></span>
                        <span class="handle cursor-move text-gray-400">&#9776;</span>
                    </div>
                    <ul class="ml-6 mt-2 space-y-1">
                        <template x-for="item in category.items" :key="item.id">
                            <li class="flex items-center justify-between bg-gray-50 rounded p-1">
                                <span x-text="item.name"></span>
                                <span class="handle cursor-move text-gray-400">&#9776;</span>
                            </li>
                        </template>
                    </ul>
                </li>
            </template>
        </ul>
        <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded" @click="saveOrder">Save Order</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sortable', () => ({
                // ...implement drag-and-drop logic here...
            }));
        });
    </script>
</div>
@endsection
