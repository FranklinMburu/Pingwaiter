@extends('layouts.app1')
@section('title')
    This link has expired
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header text-center"><h4>This link has expired</h4></div>
                <div class="card-body text-center">
                    <h6>Please re-scan the QR code at your table to view the menu.</h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection