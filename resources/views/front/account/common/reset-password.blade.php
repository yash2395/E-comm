@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger">
         {{Session::get('error')}}
        </div>
    @endif
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Reset Password</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form action="{{route('front.processRequestPassword')}}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{$token}}">
                <h4 class="modal-title">Reset Password</h4>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="New Password" @error('password') is-invaild @enderror name="password" value="{{old('password')}}">
                    <p class="text-danger mt-1 mb-0">
                        @error('password')
                            {{$message}}
                        @enderror
                    </p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" @error('confirm_password') is-invaild @enderror name="confirm_password" value="{{old('confirm_password')}}">
                    <p class="text-danger mt-1 mb-0">
                        @error('confirm_password')
                            {{$message}}
                        @enderror
                    </p>
                </div>
               
                <div class="form-group small">
                    {{-- <a href="{{route('front.forgotPassword')}}" class="forgot-link">Forgot Password?</a> --}}
                </div> 
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">              
            </form>			
            <div class="text-center small"><a href="{{route('account.login')}}">Login</a></div>
        </div>
    </div>
</section>
@endsection