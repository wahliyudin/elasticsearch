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
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <input type="text" name="search" class="form-control">
                    </div>
                    <div class="card-body">
                        <table class="table table-products">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('assets/datatables/datatables.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var datatable = $('.table-products').DataTable({
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
                ]
            });
            $('input[name="search"]').change(function(e) {
                e.preventDefault();
                datatable.ajax.reload();
            });
        });
    </script>
</body>

</html>
