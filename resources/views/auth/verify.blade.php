@extends('auth.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12 text-center mt-5">
            <h1 class="text-white">{{ getSetting('app-name')->value }}</h1>
            <h3 class="text-white">Client Enlistment</h3>
        </div>
        @if (session('verify_error'))
        <div class="col-md-6 text-center">
            <div class="alert alert-danger" role="alert">
                {{ session('verify_error') }}
            </div>
        </div>
        @endif
        {{-- @dd($clientUser) --}}
        <div class="col-md-6">
            <div class="card my-5">
                <div class="card-header">{{ __('Set Password') }}</div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <p class="mb-3">
                        {{ __('Set your client account password to enroll.') }}<br>
                        <strong>Your Email : </strong>{{ $clientUser->email }}
                    </p>

                    <form class="d-inline" method="POST" action="{{ route('verification.verify.save') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $clientUser->id }}">
                        <div class="form-group">
                            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="Password" value="{{ old('password') }}">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <p class="text-secondary font-weight-light font-italic" style="font-size: 0.8rem;">At least 8 characters, one letter, one number, one special character</p>
                        </div>
                        <div class="form-group">
                            <input id="confirm_password" type="password" class="form-control form-control-user @error('confirm_password') is-invalid @enderror" name="confirm_password" placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                            @error('confirm_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="text-right mt-4">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
