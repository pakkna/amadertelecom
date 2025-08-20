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
                    {{ __('Confirm Password') }}</div>

                <div class="card-body">
                    {{ __('Please confirm your password before continuing.') }}

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')
                                }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm Password') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
