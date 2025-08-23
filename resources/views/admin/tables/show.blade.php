@extends('layouts.main')
@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Table QR Code</h1>
    <div class="bg-white p-6 rounded shadow flex flex-col items-center">
        <div class="mb-4">
            <span class="font-semibold">Table Number:</span> {{ $table->table_number }}
        </div>
        <div class="mb-4">
            <img src="data:image/png;base64,{{ base64_encode($table->qr_code) }}" alt="QR Code" class="w-48 h-48 border rounded">
        </div>
        <div>
            <a href="{{ route('table.menu', ['table' => $table->id]) }}" class="btn btn-primary">Go to Customer Menu</a>
        </div>
    </div>
</div>
@endsection
