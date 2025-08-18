@extends('layouts.app')

@section('title', 'Manage Subscription')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Restaurant Subscription</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (auth()->user()->subscription_status === 'active')
                            <div class="alert alert-info">
                                <p>You have an active subscription.</p>
                                <p>Next billing date: {{ auth()->user()->subscription_end_date->format('M d, Y') }}</p>
                                <form action="{{ route('subscription.cancel') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                                </form>
                            </div>
                        @else
                            <p>Subscribe to continue using our restaurant services for $20/month.</p>

                            <form action="{{ route('subscription.process') }}" method="POST" id="subscription-form">
                                @csrf

                                <div class="form-group">
                                    <label for="card-element">Credit or debit card</label>
                                    <div id="card-element" class="form-control">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <div id="card-errors" role="alert" class="text-danger"></div>
                                </div>

                                <button type="submit" class="btn btn-primary">Subscribe for $20/month</button>
                            </form>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-danger" href="/logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ config('services.stripe.key') }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('subscription-form');
        var errorElement = document.getElementById('card-errors');

        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) {
                        errorElement.textContent = result.error.message;
                    } else {
                        var hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', result.token.id);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        }
    </script>
@endpush
