@extends('front.layouts.app')

@section('content')
    <section class="container">
        <div class="row justify-content-center">

            <div class="col-md-6 text-center py-5">
                @if (Session::has('success'))
                <div class="alert alert-success">
                    {{Session::get('success')}}
                </div>
                @endif
                
                <h1>Thank You!</h1>
                <p>Your Order Id is:{{$orderid}}</p>
            </div>
        </div>
    </section>
@endsection