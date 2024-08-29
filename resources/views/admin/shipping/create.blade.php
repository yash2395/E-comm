@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Managment</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('category') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        @endif

        <div class="container-fluid">
            <form action="{{ route('shipping.store') }}" method="post" id="categoryForm" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="mb-3">
                                    {{-- <label for="name">Name</label> --}}
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select a Country</option>
                                        @if ($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{$country->name}}</option>
                                            @endforeach
                                            <option value="rest_of_worl">Rest of the world</option>
                                        @endif
                                    </select>
                                    @error('country')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                <div class="text-danger">
                                    @error('amount')
                                        {{$message}}
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                @if ($shippingCharges->isNotEmpty())
                                @foreach ($shippingCharges as $shippingCharge)
                                <tr>
                                    <td>{{ $shippingCharge->id }}</td>
                                    <td>
                                        {{-- Using ternary operator to change the string getting from db --}}
                                        {{ ($shippingCharge->country_id == 'rest_of_worl') ? 'Rest Of The World' : $shippingCharge->name}}
                                    </td>
                                    <td>{{ $shippingCharge->amount}}</td>
                                    <td>
                                        <a href="{{ route('shipping.edit',$shippingCharge->id) }}" class="btn btn-primary">Edit</a>
                                        <a href="" class="btn btn-danger delete">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('customJs')
    <script>
        // $(document).ready(function() {
        //     $('#name').on('input', function() {
        //         var name = $(this).val();
        //         var slug = name.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-');
        //         $('#slug').val(slug);
        //     });
        // });



        // Dropzone.autoDiscover = false;
        //     const dropzone = $('#image').dropzone({
        //         init: function(){
        //             this.on('addedfile',function(file){
        //                 if (this.files.length > 1) {
        //                 this.removeFile(this.files[0]);
        //                 }
        //             });
                    
        //         },
        //         url: "{{ route('temp-images.create') }}",
        //             maxFiles: 1,
        //             paramName: 'image',
        //             addRemoveLinks: true,
        //             acceptedFiles: "image/jpeg, image/png, image/gif",
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(file, response) {
        //                 // $("#image_id").val(response.image_id);
        //                 alert('hi');
        //                 console.log(response)

        //             }
        //     });

        

    </script>


        

@endsection()
