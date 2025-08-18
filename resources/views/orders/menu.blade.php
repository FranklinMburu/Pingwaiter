@extends('layouts.app1')
@section('title')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Your HTML content goes here -->
            <div class="card">
                <div class="card-body text-center">
                    <h3>Table Code: {{ $table->table_code }} </h3>
                    <p>Size: {{ $table->size }}</p>
                    <p>Location: {{ $table->location }}</p>
                    <p>{{ $table->description }}</p>
                </div>

                <div class="card-header border-bottom" style="display: flex; justify-content: space-between;">
                    <h3 class="card-title">Order list</h3>
                    {{--                    <button type="button" class="btn btn-dark btn-sm add">Add</button> --}}

                </div>
                <div class="card-datatable">
                    <div id="message" class="w-100 mb-10" style="margin: 10px;"></div>
                    <div style="width:98%; margin-left:1%;">
                        <div class="table-responsive">

                            <table id="laravel-datatable-order"
                                class="display table-bordered table-striped table-hover dt-responsive mb-0 dataTable no-footer"
                                style="width: 100%;" role="grid" aria-describedby="laravel-datatable-order">

                                <thead>
                                    <tr role="row">
                                        <th>ACTION</th>
                                        <th>#</th>
                                        <th>ORDER NUMBER</th>
                                        <th>FOOD CODE</th>
                                        <th>FOOD NAME</th>
                                        <th>STYLE</th>
                                        <th>QUANTITY</th>
                                        <th>PRICE</th>
                                        <th>REMARK</th>
                                        <th>STATUS</th>
                                    </tr>
                                    <tr class="filters text-xs">
                                        <th></th>
                                        <th></th>
                                        <th><input type="text" placeholder="Search order #"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Search code"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Search name"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Search style"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Qty" class="form-control form-control-sm" />
                                        </th>
                                        <th><input type="text" placeholder="Price"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Remark"
                                                class="form-control form-control-sm" /></th>
                                        <th><input type="text" placeholder="Status"
                                                class="form-control form-control-sm" /></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="display: flex; justify-content: space-between">
                    <button class="btn btn-dark" onclick="submitToKitchen({{ $cookieValue }})"
                        id="submitToKitchenBtn">Submit To Kitchen</button>
                    <a href="{{ url('table', $table->id) }}" class="btn btn-danger"
                        style="background: white; color: black;">back</a>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!-- BEGIN: Student Add modal  -->
    <div class="modal fade text-left" id="inlineForm" data-keyboard="false" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="foodlbl" aria-hidden="true" style="overflow-y: scroll;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tablelbl">Add Order</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="Register">
                    {{ csrf_field() }}
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <!-- Category Selection -->
                            <div class="col-md-5">
                                <label><strong>Food Category</strong> <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-control select2-category">
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="category_id-error"></span>
                            </div>

                            <!-- Food Selection (will be populated based on category) -->
                            <div class="col-md-5">
                                <label><strong>Food</strong> <span class="text-danger">*</span></label>
                                <select name="item_id" id="item_id" class="form-control select2-food">
                                    <option value="">Select food</option>
                                </select>
                                <span class="text-danger" id="item_id-error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card-body text-center">
                                <div class="food-gallery-container">
                                    <!-- Image gallery container -->
                                    <div id="food-gallery" class="d-flex flex-wrap justify-content-center"
                                        style="display: none;">
                                        <!-- Images will be added here dynamically -->
                                    </div>

                                    <!-- Video container -->
                                    <div id="food-video-container" class="mt-3 text-center" style="display: none;">
                                        <video id="food-video" controls class="img-fluid" style="max-height: 200px;">
                                            <source src="" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                                <h3>Food Code: <span id="food_code"></span></h3>
                                <p><span id="description"></span></p>
                                <h3>Price: <span id="price"></span></h3>
                            </div>
                        </div>
                        <input type="hidden" name="table_id" id="table_id" value="{{ $table->id }}">
                        <div class="row justify-content-center">
                            <!-- food style -->
                            <div class="col-md-5">
                                <label><strong>Food Style</strong> <span class="text-danger">*</span></label>
                                <select name="style" id="style" class="form-control select2-style">
                                    <option value="">Select food style</option>
                                </select>
                                <span class="text-danger" id="style-error"></span>
                            </div>
                            <!-- Quantity -->
                            <div class="col-md-5">
                                <label><strong>Quantity</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                    placeholder="e.g. 2" value="{{ old('quantity') }}">
                                <span class="text-danger" id="quantity-error"></span>
                            </div>
                        </div>
                        <div class="row justify-content-center">

                            <div class="col-md-8">
                                <label><strong>Remark</strong></label>
                                <textarea name="remark" id="remark" class="form-control" rows="3" placeholder="Write remark...">{{ old('remark') }}</textarea>
                                <span class="text-danger" id="remark-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="savebutton" type="button" class="btn btn-dark">Save</button>
                        <button id="closebutton" type="button" class="btn btn-danger" onclick="closeModal()"
                            data-dismiss="modal" style="background: white; color: black;">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        var food_style = null;
        var errorcolor = "#ffcccc";
        $(function() {
            cardSection = $('#page-block');

            // Initialize Select2 for category dropdown
            $('.select2-category').select2({
                placeholder: 'Search and select category',
                allowClear: false,
                width: '100%',
                theme: 'classic'
            });

            // Initialize Select2 for food dropdown
            $('.select2-food').select2({
                placeholder: 'Search and select food',
                allowClear: false,
                width: '100%',
                theme: 'classic',
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    if (data.loading) return data.text;
                    var $result = $(
                        '<span>' + data.text + '</span>'
                    );
                    return $result;
                }
            });

            // Initialize Select2 for food style dropdown
            $('.select2-style').select2({
                placeholder: 'Search and select style',
                allowClear: false,
                width: '100%',
                theme: 'classic'
            });

            // When category changes, update food items
            $('.select2-category').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    // Clear current food selection
                    $('#item_id').empty().append('<option value="">Select food</option>');

                    // Reset food details
                    $('#food_image').attr('src', '').css({
                        "display": "none"
                    });
                    $('#food_code').html('');
                    $('#description').html('');
                    $('#price').html('');

                    // Clear and reset style dropdown
                    $('#style').empty().append('<option value="">Select food style</option>');
                    $('#style').trigger('change'); // Refresh Select2

                    // Get foods for selected category
                    $.get('/getfoodsbycategory/' + categoryId, function(data) {
                        if (data.foods && data.foods.length > 0) {
                            $.each(data.foods, function(index, food) {
                                $('#item_id').append('<option value="' + food.id + '">' +
                                    food.name + '</option>');
                            });
                            $('#item_id').trigger('change'); // Refresh Select2
                        }
                    });
                }
            });

            // When food changes, get food details
            $('.select2-food').on('change', function(e) {
                var isUserAction = !!e.originalEvent;
                // console.log('isUserAction: ' + isUserAction);
                setTimeout(function() {
                    selectFood(isUserAction);
                }, 500);

            });
        });
        // Initialize Fancybox globally
        Fancybox.bind("[data-fancybox]", {
            // Default options
            loop: true,
            buttons: [
                "zoom",
                "slideShow",
                "fullScreen",
                "thumbs",
                "close"
            ],
            animationEffect: "fade",
            transitionEffect: "fade",
            thumbs: {
                autoStart: true
            }
        });

        let ftable = $('#laravel-datatable-order').DataTable({
            destroy: true,
            processing: true,
            // serverSide: true,
            deferRender: true,
            scrollY: 400,
            scroller: true,
            searchHighlight: true,
            orderCellsTop: true,
            fixedHeader: true,
            dom: '<"flex items-center justify-between mb-4"<"search-container"f><"length-container"l>>rt<"bottom flex justify-between items-center"<"info-container"i><"pagination-container"p>>',
            initComplete: function() {
                // Create the button
                var inviteBtn = $(
                    '<button type="button" class="btn btn-primary add" style="background: black; color: white;height: 35px;vertical-align: top;"><i class="fa fa-plus mr-1"></i>Add</button>'
                );
                // Insert it before the search box
                $('.dataTables_filter').prepend(inviteBtn);
                $('.add').click(function() {
                    $('#tablelbl').html('Add Order');
                    $('#item_id').val('');
                    $('#food_image').attr('src', ''); // or a placeholder
                    $('#food_image').css({
                        "display": "none"
                    });
                    $('#food_code').html('');
                    $('#description').html('');
                    $('#price').html('');
                    $('#remark').val('');
                    $('#quantity').val('');
                    $('#remark-error').html('');
                    $('#quantity-error').html('');
                    $('#edit_id').val('');
                    $('#style').empty();
                    $('#savebutton').html('Save');
                    $('#inlineForm').modal('show');
                });
            },

            // dom: 'Bfrtip', // Add l before B to include lengthMenu
            lengthMenu: [
                [10, 25, 50, 500, -1],
                [10, 25, 50, 500, "All"]
            ],
            language: {
                search: '',
                searchPlaceholder: "Search here"
            },
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/getorderlist/' + {{ $table->id }},
                type: 'DELETE',
                beforeSend: function() {

                },
                complete: function() {

                },
            },
            columns: [{
                    data: null,
                    render: function(data, type, full, meta) {
                        if (data.status === 'Editable') {
                            return `
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info mr-2" onclick="orderEditFn(${data.id})" title="Edit order">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="orderDeleteFn(${data.id})" title="Delete order">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                `;
                        } else {
                            return '';
                        }
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'group_number',
                    name: 'group_number'
                },
                {
                    data: 'item.item_code',
                    name: 'item.item_code'
                },
                {
                    data: 'item.name',
                    name: 'item.name'
                },
                {
                    data: 'style_name',
                    name: 'style_name'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'item.price',
                    name: 'item.price'
                },
                {
                    data: 'remark',
                    name: 'remark'
                },
                {
                    data: 'status',
                    name: 'status'
                }
            ],
            drawCallback: function(settings) {
                // Check if there are any editable orders
                checkEditableOrders();
            },
            initComplete: function() {
                // $('.dataTables_length select').addClass('form-input').removeClass(
                //     'form-control form-control-sm');
                // $('.dataTables_filter input').addClass('form-input').removeClass(
                //     'form-control form-control-sm');

                const api = this.api();

                api.columns().every(function() {
                    const column = this;
                    $('input', $('.filters th').eq(column.index()))
                        .off()
                        .on('keyup change clear', function() {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
            },
        });

        $('#laravel-datatable-order thead .filters input').on('keyup change', function() {
            let colIndex = $(this).parent().index();
            ftable.column(colIndex).search(this.value).draw();
        });

        function checkEditableOrders() {
            var hasEditableOrders = false;

            // Get all rows from the table
            var tableData = ftable.rows().data();

            // Loop through the data to check for editable orders
            for (var i = 0; i < tableData.length; i++) {
                console.log(tableData[i].status)
                if (tableData[i].status === 'Editable') {
                    hasEditableOrders = true;
                    break;
                }
            }

            // Enable or disable the submit button based on whether there are editable orders
            $('#submitToKitchenBtn').prop('disabled', !hasEditableOrders);
        }


        $('#savebutton').click(function() {
            var orderForm = $('#Register');
            var formData = orderForm.serialize();
            var id = $('#edit_id').val() ? $('#edit_id').val() : 'add';

            $.ajax({
                url: '/createupdateorder/' + id,
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#savebutton').text(id !== 'add' ? 'Updating...' : 'Saving...').prop("disabled",
                        true);
                    $('.text-danger').text(''); // Clear previous errors
                },
                success: function(data) {
                    if (data.errors) {
                        $.each(data.errors, function(key, value) {
                            $('#' + key + '-error').text(value[0]);
                        });
                        alert_toast('Check your input', 'error');
                        $('#savebutton').text(id !== 'add' ? 'Update' : 'Save').prop("disabled", false);
                    } else if (data.success) {
                        alert_toast(data.success, 'success');
                        var cTable = $('#laravel-datatable-order').dataTable();
                        cTable.fnDraw(false);
                        $('#savebutton').text('Save').prop("disabled", false);
                        $('#inlineForm').modal('hide');
                    } else if (data.cookie_error) {
                        alert_toast(data.cookie_error, 'error');
                        $('#savebutton').text(id !== 'add' ? 'Update' : 'Save').prop("disabled", false);
                    }
                }
            });
        });

        function orderEditFn(record_id) {
            $('#edit_id').val(record_id);
            $('#tablelbl').html('Edit Order');
            $('#savebutton').html('Update');

            $.get('/editorder/' + record_id, function(data) {
                if (data.order) {
                    // Set category first
                    $('#category_id').val(data.order.item.category_id).trigger('change');
                    food_style = data.order.style;

                    setTimeout(function() {
                        // Set the food item and trigger change to load its details
                        $('#item_id').val(data.order.item_id).trigger('change');

                        // Set quantity and remark
                        $('#quantity').val(data.order.quantity);
                        $('#remark').val(data.order.remark);

                    }, 500);
                }
            });

            $('#inlineForm').modal('show');
        }

        function orderDeleteFn(record_id) {
            var check = confirm('Are you sure to delete this data?');
            if (check === true) {
                deleteOrder(record_id);
            }
        }

        function deleteOrder(record_id) {
            $.get('/deleteorder/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    var fTable = $('#laravel-datatable-order').dataTable();
                    fTable.fnDraw(false);
                } else {
                    alert_toast('An error occured please try again!', 'error');
                }
            });
        }

        function selectFood(isUserAction) {
            $.get('/itemdetail/' + $('#item_id').val(), function(data) {
                if (data.item) {
                    $('#food_code').html(data.item.item_code);
                    $('#food_name').html(data.item.name);
                    $('#description').html(data.item.description);
                    $('#price').html(data.item.price);

                    // Clear existing galleries
                    $('#food-gallery').empty().hide();
                    $('#food-video-container').hide();

                    // Handle images display
                    if (data.item.picture) {
                        const pictures = Array.isArray(data.item.picture) ? data.item.picture : [data.item.picture];

                        if (pictures.length > 0) {
                            // Create gallery items
                            const galleryId = 'food-gallery-' + data.item.id;
                            $('#food-gallery').show();

                            pictures.forEach(function(pic, index) {
                                const imageUrl = '/uploads/food/pictures/' + pic;
                                const imageHtml = `
                                    <div class="m-1">
                                        <a href="${imageUrl}" data-fancybox="${galleryId}">
                                            <img src="${imageUrl}" alt="Food Image ${index+1}"
                                                 class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </a>
                                    </div>
                                `;
                                $('#food-gallery').append(imageHtml);
                            });
                        }
                    }

                    // Handle video display
                    if (data.item.video) {
                        $('#food-video-container').show();
                        $('#food-video source').attr('src', '/uploads/food/videos/' + data.item.video);
                        $('#food-video')[0].load(); // Reload the video element
                    }

                    // Handle food style with Select2
                    let styles = data.item.styles;
                    let styleSelect = $('#style');
                    styleSelect.empty(); // Clear previous options

                    // Add default "Select Food Style" option
                    styleSelect.append('<option value="">Select Food Style</option>');

                    if (styles && styles.length > 0) {
                        // Iterate through style objects
                        styles.forEach(function(style) {
                            styleSelect.append(
                                `<option value="${style.id}">${style.name}</option>`
                            );
                        });
                    }
                    if (!isUserAction) {
                        styleSelect.val(food_style);
                    }

                    styleSelect.trigger('change');

                } else {
                    alert_toast('an error occured', 'error');
                }
            });
        }

        function submitToKitchen(record_id) {

            var check = confirm('Are you sure to send to kitchen this order?');
            if (check === true) {
                submitOrder(record_id);
            }
        }

        function submitOrder(record_id) {

            $.get('/submittokithcen/' + record_id, function(data) {
                if (data.success) {
                    alert_toast(data.success, 'success');
                    var fTable = $('#laravel-datatable-order').dataTable();
                    fTable.fnDraw(false);
                } else if (data.exit) {
                    alert_toast('Order already submitted', 'error')
                } else {
                    alert_toast('An error ocurred', 'error')
                }
            });
        }

        function removeNameValidation() {
            $('#name-error').html('');
        }

        function removeSlugValidation() {
            $('#slug-error').html('');
        }


        function closeModal() {
            $('#category_id').val('').trigger('change');
            $('#item_id').val('');
            $('#food-gallery').empty().hide();
            $('#food-video-container').hide();
            $('#food-video source').attr('src', '');
            $('#food_code').html('');
            $('#description').html('');
            $('#price').html('');
            $('#remark').val('');
            $('#quantity').val('');
            $('#style').empty().append('<option value="">select food style</option>');
            $('#remark-error').html('');
            $('#quantity-error').html('');
            $('#item_id-error').html('');
            $('#category_id-error').html('');
            $('#style-error').html('');
            $('#edit_id').val('');
            $('#savebutton').html('Save');
            food_style = null;
        }
    </script>
@endsection
