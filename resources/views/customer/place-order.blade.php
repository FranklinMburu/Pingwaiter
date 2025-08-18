@extends('layouts.customer')

@section('title', 'Place Order - Table ' . $tableCode)

@push('styles')
    <style>
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .category-btn {
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .category-btn.active {
            background: #8065ee;
            color: white;
        }

        .category-btn:not(.active) {
            background: #f1f5f9;
            color: #64748b;
        }

        .category-btn:not(.active):hover {
            background: #e2e8f0;
            color: #475569;
        }

        .menu-item {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            border-color: #8065ee;
            transform: translateY(-2px);
        }

        .cart-floating {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #8065ee;
            color: white;
            padding: 16px 32px;
            border-radius: 25px;
            font-weight: 600;
            z-index: 1000;
            display: none;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .cart-floating.show {
            display: flex;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 16px;
            }
        }
    </style>
@endpush

@section('content')
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="text-white text-lg font-bold animate-pulse">
            Placing your order...
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="content-card text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Table {{ $tableCode }}</h2>
        </div>

        <div class="content-card mb-6">
            <div class="flex flex-wrap gap-3 mb-6">
                @foreach ($categories as $cat)
                    <button class="category-btn {{ $loop->first ? 'active' : '' }}"
                        onclick="showCategory('{{ Str::slug($cat->name) }}')">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        @foreach ($categories as $cat)
            <div id="{{ Str::slug($cat->name) }}" class="category-section {{ !$loop->first ? 'hidden' : '' }}">
                <div class="content-card">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">{{ $cat->name }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($cat->foodItems as $item)
                            <div class="menu-item p-4">
                                <div class="flex gap-4">
                                    <img src="{{ isset($item->picture[0]) ? asset('uploads/food/pictures/' . $item->picture[0]) : asset('placeholder.svg') }}"
                                        alt="{{ $item->name }}" class="w-20 h-20 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $item->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">{{ $item->description }}</p>
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-lg font-bold text-gray-900">{{ $restaurant['currency_symbol'] }}
                                                {{ $item->price }}</span>
                                            <div class="flex items-center gap-2">
                                                <button class="quantity-btn bg-gray-100 text-gray-600 hover:bg-gray-200"
                                                    onclick="decreaseQuantity('{{ $item->item_code }}')">-</button>
                                                <span id="{{ $item->item_code }}-qty"
                                                    class="w-8 text-center font-medium">0</span>
                                                <button class="quantity-btn bg-purple-500 text-white hover:bg-purple-600"
                                                    onclick="increaseQuantity('{{ $item->item_code }}', '{{ $item->name }}', {{ $item->price }})">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </main>

    <div class="cart-floating show flex-col w-full" id="cartFloating">
        <div class="flex items-center justify-between w-full mb-2 px-4">
            <div class="flex items-center gap-2">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7Z" />
                </svg>
                <span id="cartText">View Cart</span>
                <div class="bg-white bg-opacity-20 rounded-full px-2 py-1 text-sm font-bold" id="cartCount">0</div>
            </div>
        </div>

        <button onclick="submitOrder()"
            class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 rounded-xl text-white font-semibold">
            Place Order
        </button>
    </div>
@endsection

@push('scripts')
    <script>
        let cart = {};
        let cartTotal = 0;
        let cartItemCount = 0;

        function showCategory(category) {
            document.querySelectorAll('.category-section').forEach(section => {
                section.classList.add('hidden');
            });

            document.getElementById(category).classList.remove('hidden');

            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        function increaseQuantity(itemId, itemName, price) {
            if (!cart[itemId]) {
                cart[itemId] = {
                    name: itemName,
                    price: price,
                    quantity: 0
                };
            }
            cart[itemId].quantity++;
            updateQuantityDisplay(itemId);
            updateCart();
        }

        function decreaseQuantity(itemId) {
            if (cart[itemId] && cart[itemId].quantity > 0) {
                cart[itemId].quantity--;
                if (cart[itemId].quantity === 0) {
                    delete cart[itemId];
                }
                updateQuantityDisplay(itemId);
                updateCart();
            }
        }

        function updateQuantityDisplay(itemId) {
            const qtyElement = document.getElementById(itemId + '-qty');
            const quantity = cart[itemId] ? cart[itemId].quantity : 0;
            qtyElement.textContent = quantity;
        }

        function updateCart() {
            cartItemCount = 0;
            cartTotal = 0;

            Object.values(cart).forEach(item => {
                cartItemCount += item.quantity;
                cartTotal += item.price * item.quantity;
            });

            const cartFloating = document.getElementById('cartFloating');
            const cartCount = document.getElementById('cartCount');
            const cartText = document.getElementById('cartText');

            if (cartItemCount > 0) {
                cartFloating.classList.add('show');
                cartCount.textContent = cartItemCount;
                cartText.textContent = `View Cart ($${cartTotal.toFixed(2)})`;
            } else {
                cartFloating.classList.remove('show');
            }
        }

        function viewCart() {
            if (cartItemCount > 0) {
                localStorage.setItem('cart', JSON.stringify(cart));
            }
        }

        function submitOrder() {
            const tableId = "{{ $tableCode }}";
            const orders = Object.entries(cart).map(([key, value]) => ({
                item_id: key,
                quantity: value.quantity,
                style: 1,
                remark: "",
            }));

            if (orders.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Cart is empty',
                    text: 'Please add items to your cart before placing an order.',
                });
                return;
            }

            document.getElementById('loadingOverlay').classList.remove('hidden');

            fetch(`/customer/order/store/${tableId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        orders
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingOverlay').classList.add('hidden');
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order placed!',
                            text: 'Your order has been successfully submitted.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            localStorage.removeItem('cart');
                            location.reload();
                        });
                    } else if (data.cookie_error) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Order limit reached',
                            text: data.cookie_error,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong while placing your order.',
                        });
                    }
                })
                .catch(() => {
                    document.getElementById('loadingOverlay').classList.add('hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Unable to place order. Please check your internet connection.',
                    });
                });
        }
    </script>
@endpush
