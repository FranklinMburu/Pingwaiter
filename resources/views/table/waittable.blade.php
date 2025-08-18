@extends('layouts.app1')

@section('subscription')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="justify-content: space-between; display: flex;">
                        @if (isset($table->restaurant->name))
                            <p>{{ $table->restaurant->name }}</p>
                        @endif
                        <p>Customer Menu</p>
                    </div>

                    <div class="card-body text-center">
                        <h3>Table Code: {{ $table->table_code }} </h3>
                        <p>Size: {{ $table->size }}</p>
                        <p>Location: {{ $table->location }}</p>
                        <p>Description: {{ $table->description }}</p>
                    </div>

                    <div class="card p-3 text-center">
                        @php
                            $alreadyCalling = \App\Models\TablePing::where('table_id', $table->id)
                                ->where('is_attended', false)
                                ->exists();
                        @endphp

                        <button class="btn btn-success mb-2" id="callWaiterBtn"
                            {{ $alreadyCalling ? 'disabled' : '' }}>
                            {{ $alreadyCalling ? 'Calling...' : 'Call Waiting' }}
                        </button>
                        @if ($alreadyCalling)
                            <button class="btn btn-danger mb-2" id="callWaiterBtn1"
                                onclick="endCalling({{ $table->id }})" {{ $alreadyCalling ? '' : 'disabled' }}>
                                {{ $alreadyCalling ? 'Stop Calling' : 'No Call' }}
                            </button>
                        @endif

                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ url('/setup-tables') }}" class="btn btn-danger">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function endCalling(record_id) {
            $.get('/endcalling/' + record_id, function(data) {
                if (data.success) {
                    alert_toast('Calling has been end!');
                    // Optional: Change button state or text to "Calling..."
                    $('#callWaiterBtn').text('Call Waiting').prop('disabled', true);
                    $('#callWaiterBtn1').css({
                        'display': 'none'
                    })
                } else {
                    alert_toast('An error occured');
                }
            });
        }

        @if ($alreadyCalling)
            const message = `Table {{ $table->table_code }}} is calling for a waiter.`;
            const utterance = new SpeechSynthesisUtterance(message);
            speechSynthesis.speak(utterance);
        @endif
    </script>
@endsection
