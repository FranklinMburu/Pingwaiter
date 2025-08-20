@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto mt-16 p-8 bg-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-center">{{ __('account.choose_type') }}</h2>
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    <div x-data="{ selected: '{{ old('account_type') }}' }" class="space-y-4">
        <template x-for="type in Object.keys(@json(__('account.types')))" :key="type">
            <div>
                <button type="button"
                    :class="selected === type ? 'bg-primary-500 text-white border-primary-500 ring-2 ring-primary-500' : 'bg-gray-100 text-gray-700 border-gray-300'"
                    class="w-full px-6 py-4 rounded-lg border font-semibold flex items-center justify-between transition duration-150 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    @click="selected = type"
                    :aria-pressed="selected === type"
                    :aria-label="@json(__('account.types'))[type]"
                    :tabindex="selected === type ? 0 : -1">
                    <span class="capitalize" x-text="@json(__('account.types'))[type]"></span>
                    <span x-show="selected === type" class="ml-2 text-lg">✔️</span>
                </button>
            </div>
        </template>
        <form method="POST" action="{{ route('account.type.save') }}" class="mt-6">
            @csrf
            <input type="hidden" name="account_type" x-model="selected">
            <button type="submit" class="w-full py-3 rounded-lg bg-primary-500 text-white font-bold text-lg disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-primary-500" :disabled="!selected">
                {{ __('account.continue') }}
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
