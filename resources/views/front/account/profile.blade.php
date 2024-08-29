@extends('front.layouts.app')


@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            @include('front.account.common.message')
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <div class="card-body p-4">
                    <form action="{{route('account.updateProfile')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="mb-3">   
                                
                                <label for="name">Name</label>
                                <input value="{{ $user->name }}" type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('name')
                                        {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="mb-3">            
                                <label for="email">Email</label>
                                <input value="{{ $user->email }}" type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('email')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="phone">Phone</label>
                                <input value="{{ $user->phone }}" type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('phone')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>

                            

                            <div class="d-flex">
                                <button type="submit" class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                    </div>
                    {{-- @php
                        print_r($errors->all())
                    @endphp --}}
                    <div class="card-body p-4">
                    <form action="{{route('account.updateAddress')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">   
                                <label for="first_name">First Name</label>
                                <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name" placeholder="Enter Your First Name" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('first_name')
                                        {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">   
                                <label for="last_name">Last Name</label>
                                <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name" placeholder="Enter Your Last Name" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('last_name')
                                        {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">            
                                <label for="email">Email</label>
                                <input value="{{ (!empty($address)) ? $address->email : '' }}" type="email" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('email')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="mobile">Mobile</label>
                                <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile" placeholder="Enter Your Mobile No." class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('mobile')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Select a Country</option>
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{(!empty($address) && $address->country_id == $country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                        
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-danger mt-1 mb-0">
                                    @error('mobile')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="mb-3">                                    
                                <label for="address">Address</label>
                                <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                <p class="text-danger mt-1 mb-0">
                                    @error('mobile')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="apartment">Apartment</label>
                                <input value="{{ (!empty($address)) ? $address->apartment : '' }}" type="text" name="apartment" id="apartment" placeholder="Appartment" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('apartment')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="city">City</label>
                                <input value="{{ (!empty($address)) ? $address->city : '' }}" type="text" name="city" id="city" placeholder="City" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('city')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="state">State</label>
                                <input value="{{ (!empty($address)) ? $address->state : '' }}" type="text" name="state" id="state" placeholder="State" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('state')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">                                    
                                <label for="zip">Zip</label>
                                <input value="{{ (!empty($address)) ? $address->zip : '' }}" type="text" name="zip" id="zip" placeholder="Zip" class="form-control">
                                <p class="text-danger mt-1 mb-0">
                                    @error('zip')
                                    {{$message}}
                                    @enderror
                                </p>
                            </div>

                            <div class="d-flex">
                                <button type="submit" class="btn btn-dark">Update</button>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#profileForm").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{route("account.updateProfile")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                
            }
        });
    });

</script>


@endsection