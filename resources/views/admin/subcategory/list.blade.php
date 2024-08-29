<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <!-- Custom CSS -->
    <style>
        /* Add custom styles here */
        .action-btns {
            width: 100px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col-sm-12">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <h2 class="text-center mb-4">View Posts</h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('post.create') }}" class="btn btn-primary mr-2">Add Post</a>
                <button id="filterByDateBtn" class="btn btn-secondary">Filter by Date</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <!-- Table header -->
            </table>
            <div class="d-flex justify-content-center mt-4 clear-fix">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // // Initialize datepicker when button is clicked
            // $('#filterByDateBtn').on('click', function() {
            //     // Destroy any existing datepicker instances to prevent conflicts
            //     // $('#filterByDateBtn').datepicker('destroy');

            //     // Initialize datepicker
            //     $('#filterByDateBtn').datepicker({
            //         format: 'yyyy-mm-dd',
            //         autoclose: true
            //     }).on('changeDate', function(e) {
            //         var selectedDate = e.format('yyyy-mm-dd');
            //         // Call AJAX to filter data by selected date
            //         // Example:
            //         // $.get('filterByDate', { date: selectedDate }, function(data) {
            //         //     // Update table with filtered data
            //         // });
            //     });
            // });

            $('#filterByDateBtn').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                container: '.container' // specify the container to avoid datepicker being cut off
            });

            // Show datepicker when button is clicked
            $('#filterByDateBtn').on('click', function() {
                $('#filterByDateBtn').datepicker('show');
            });

            // Handle date change event
            $('#filterByDateBtn').on('changeDate', function(e) {
                var selectedDate = e.format('yyyy-mm-dd');
                // Call AJAX to filter data by selected date
                // Example:
                // $.get('filterByDate', { date: selectedDate }, function(data) {
                //     // Update table with filtered data
                // });
            });
        });
        
    </script>
</body>

</html>
