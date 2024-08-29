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
                <li class="breadcrumb-item">Login</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form action="{{route('account.authenticate')}}" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" @error('email') is-invaild @enderror name="email" value="{{old('email')}}">
                    <p class="text-danger mt-1 mb-0">
                        @error('email')
                            {{$message}}
                        @enderror
                    </p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" @error('password') is-invaild @enderror name="password" value="">
                    <p class="text-danger mt-1 mb-0">
                        @error('password')
                            {{$message}}
                        @enderror
                    </p>
                </div>
                <div class="form-group small">
                    <a href="{{route('front.forgotPassword')}}" class="forgot-link">Forgot Password?</a>
                </div> 
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">              
            </form>			
            <div class="text-center small">Don't have an account? <a href="{{route('account.register')}}">Sign up</a></div>
        </div>
    </div>
</section>
@endsection