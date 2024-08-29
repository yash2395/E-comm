@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <pre>
    {{  
    var_dump($category);
    }}
    </pre> --}}
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('category')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{route('categories.update',$category->id)}}" method="post" id="categoryForm" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$category->name}}">
                                    @error('name')
								        <p class="text-danger">{{ $message }}</p>
								    @enderror
                                </div>
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$category->slug}}">
                                    @error('slug')
                                       <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="inputGroupFile01">Image</label>
                                <div class="input-group mb-3">
                                    <div class="">
                                      <input type="file" class="" id="inputGroupFile01" aria-describedby="helpid" name="image">
                                      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($category->image))
                                <div>
                                    {{-- <img src="{{storage_path('app/uploads/'.$category->image)}}" alt="" width="250"> --}}
                                    <img src="{{ asset('storage/uploads/'.$category->image) }}" alt="Image">
                                    <img src="{{asset('./storage/app/uploads/'.$category->image)}}" alt="">
                                </div>                                
                            @endif
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{($category->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                        <option {{($category->status == 2) ? 'selected' : ''}} value="2">Block</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Show On Home</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option {{($category->showHome == 'Yes') ? 'selected' : ''}} value="Yes">Yes</option>
                                        <option {{($category->showHome == 'No') ? 'selected' : ''}} value="No" >No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{route('category')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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

    
</script>

@endsection()