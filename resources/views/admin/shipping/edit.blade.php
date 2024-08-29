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

        <div class="container-fluid">
            <form action="{{ route('shipping.update',$shippingCharges->id) }}" method="post" id="categoryForm" enctype="multipart/form-data">
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
                                            {{-- @php
                                                print_r($shippingCharges);
                                            @endphp --}}
                                            @foreach ($countries as $country)
                                                <option {{($shippingCharges->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{$country->name}}</option>
                                            @endforeach
                                            <option {{($shippingCharges->country == 'rest_of_worl') ? 'selected' : '' }} value="rest_of_worl">Rest of the world</option>
                                        @endif
                                    </select>
                                    @error('country')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <input value="{{$shippingCharges->amount}}" type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
           
        </div>
        <!-- /.card -->
    </section>
@endsection


@section('customJs')
    <script>
        $(document).ready(function() {
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-');
                $('#slug').val(slug);
            });
        });



        Dropzone.autoDiscover = false;
            const dropzone = $('#image').dropzone({
                init: function(){
                    this.on('addedfile',function(file){
                        if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                        }
                    });
                    
                },
                url: "{{ route('temp-images.create') }}",
                    maxFiles: 1,
                    paramName: 'image',
                    addRemoveLinks: true,
                    acceptedFiles: "image/jpeg, image/png, image/gif",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(file, response) {
                        // $("#image_id").val(response.image_id);
                        alert('hi');
                        console.log(response)

                    }
            });

    </script>


        

@endsection()
