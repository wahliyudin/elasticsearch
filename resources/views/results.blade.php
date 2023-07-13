<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.bundle.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#create">Tambah</button>
                                    <input type="text" name="search" class="form-control">
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-products">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="row form-create">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" name="description" id="description" class="form-control"
                                            placeholder="Description">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" name="price" id="price" class="form-control"
                                            placeholder="Price">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var datatable = $('.table-products').DataTable({
                "perPage": 10,
                "ajax": {
                    "url": "/home/datatable",
                    "type": "POST",
                    "data": function(d) {
                        d.search = $('input[name="search"]').val();
                    }
                },
                "columns": [{
                        "name": "id",
                        "data": "id",
                    },
                    {
                        "name": "name",
                        "data": "name",
                    },
                    {
                        "name": "description",
                        "data": "description",
                    },
                    {
                        "name": "price",
                        "data": "price",
                    },
                    {
                        "name": "action",
                        "data": "action",
                    },
                ]
            });
            $('input[name="search"]').change(function(e) {
                e.preventDefault();
                datatable.ajax.reload();
            });

            $('.save').click(function(e) {
                e.preventDefault();
                var postData = new FormData($(`.form-create`)[0]);
                $.ajax({
                    type: 'POST',
                    url: `/home/store`,
                    processData: false,
                    contentType: false,
                    data: postData,
                    success: function(response) {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(function() {
                            datatable.ajax.reload();
                            $('#create').modal('hide');
                        });
                    },
                    error: function(jqXHR, xhr, textStatus, errorThrow, exception) {
                        if (jqXHR.status == 422) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan!',
                                text: JSON.parse(jqXHR.responseText)
                                    .message,
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: jqXHR.responseText,
                            })
                        }
                    }
                });
            });

            $('.table-products').on('click', '.btn-delete', function() {
                var product = $(this).data('product');
                Swal.fire({
                    text: "Are you sure you want to delete ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE",
                            url: `/home/${product}/destroy`,
                            dataType: "JSON",
                            success: function(response) {
                                Swal.fire({
                                    text: "You have deleted !.",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function() {
                                    datatable.ajax.reload();
                                });
                            },
                            error: function(jqXHR, xhr, textStatus, errorThrow, exception) {
                                if (jqXHR.status == 422) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Peringatan!',
                                        text: JSON.parse(jqXHR.responseText)
                                            .message,
                                    })
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: jqXHR.responseText,
                                    })
                                }
                            }
                        });
                    } else if (result.dismiss === 'cancel') {
                        Swal.fire({
                            text: "was not deleted.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            }
                        }).then(function() {});
                    }
                });

            });
        });
    </script>
</body>

</html>
