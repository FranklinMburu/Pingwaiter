@extends('layouts.app')

@push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printed_part,
            .printed_part * {
                visibility: visible;
            }

            .printed_part {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding-top: 30px;
                text-align: center;
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            }

            .printed_part h1 {
                font-size: 22px;
                font-weight: bold;
                margin-bottom: 20px;
                color: #000;
            }

            .printed_part h3 {
                font-size: 16px;
                color: #333;
                margin-top: 15px;
            }

            .no-print,
            button,
            a {
                display: none !important;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                height: auto;
            }

            @page {
                margin: 0;
                size: auto;
            }
        }
    </style>
@endpush

@section('content')
    <div class="px-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-full md:col-span-8 md:col-start-3">
                <div class="card p-6 text-center printed_part">
                    <div class="text-center my-6 no-print">
                        <button onclick="printWithGuidance()" class="btn b-solid btn-primary-solid mb-4 mx-auto">
                            <i class="fa fa-print"></i>
                            Print
                        </button>
                        <a target="_blank" href="{{ $tableUrl }}" class="btn b-solid btn-primary-solid mx-auto">
                            <i class="fa fa-external-link-alt"></i>
                            Table URL
                        </a>
                    </div>
                    <h1 class="text-3xl font-bold">
                        {{ $table->restaurant->allow_place_order ? 'Order Food or ' : '' }}Call Waiter
                    </h1>
                    <div class="my-4 flex justify-center">{!! $qrCode !!}</div>
                    <h3 class="text-2xl font-semibold">{{ $table->table_code }} - QR Code</h3>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function printWithGuidance() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-70 z-[9999] flex items-center justify-center';

            const content = document.createElement('div');
            content.className = 'bg-white rounded-lg p-6 max-w-md w-full text-left shadow-lg';
            content.innerHTML = `
            <h3 class="text-lg font-bold mb-3">Print Instructions</h3>
            <p class="mb-2">For best results:</p>
            <ol class="list-decimal list-inside text-sm text-gray-600 mb-4">
                <li>Uncheck "Headers and Footers" in print settings</li>
                <li>Set margins to "None" or "Minimum"</li>
                <li>Use "Portrait" orientation</li>
            </ol>
            <div class="text-center">
                <button id="proceed-print" class="btn b-solid btn-primary-solid mr-2 mb-4 w-full">Proceed to Print</button>
                <button id="cancel-print" class="btn b-solid btn-light-solid w-full">Cancel</button>
            </div>
        `;

            modal.appendChild(content);
            document.body.appendChild(modal);

            document.getElementById('proceed-print').addEventListener('click', function() {
                document.body.removeChild(modal);
                setTimeout(() => window.print(), 100);
            });

            document.getElementById('cancel-print').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
        }
    </script>
@endpush
