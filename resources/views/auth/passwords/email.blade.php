@extends('layouts.app')

@section('content')
<div class="container">
    <br><br><br><br>
    <div class="row justify-content-center">
        <div class="col-md-offset-5 col-md-4">
            <a href="{{ url('/login') }}">
                <div style="height:20px;width:350px;margin-bottom:110px"><img
                        src="{{asset('assets/images/diu_bus_logo.png')}}" style="width: 125%;margin-top: -30px;" alt="">
                </div> {{--{{ config('app.name', 'Laravel') }}--}}
            </a><br>

        </div>

    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"
                    style="background:linear-gradient(90deg, rgba(2,177,58,1) 14%, rgb(10, 172, 43) 65%);;color: white;font-weight: bold;;font-size:18px">
                    {{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address')
                                }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                    placeholder="User Valid Email Address">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
