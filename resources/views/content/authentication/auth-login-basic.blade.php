@extends('layouts/fullLayoutMaster')

@section('title', 'Asistencias Fay')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection
<style>

  #Logo-fay{
    display: flex;
    justify-content: center;
    padding: 10px;
    width: 350px
  
  }

  .title-fay-login{
   text-align: center;
  }

.btn-session{
  background-color: red;
}

</style>  

@section('content')
<div class="auth-wrapper auth-basic px-2">
  <div class="auth-inner my-2">
    <!-- Login basic -->
    <div class="card mb-0">
      <div class="card-body">
        <div class="d-flex justify-content-center px-2">

          <a href="#" class="brand-logo">
            <img id="Logo-fay" src="{{ asset('images/icons/logo-fay.png') }}">
           </a>
        </div>
 

       
        <form class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
          <div class="mb-1">
            <x-jet-label  class="form-label" for="email" value="{{ __('Email/Phone') }}" />
            <input
              type="text"
              class="form-control"
              id="login-email"
              name="login-email"
              placeholder="john@example.com"
              aria-describedby="login-email"
              tabindex="1"
              autofocus
            />
          </div>
        
          <div class="mb-1">
            <div class="d-flex justify-content-between">
              <x-jet-label class="form-label" for="password" value="{{ __('Password') }}" />
              
              <a href="{{url('auth/forgot-password-basic')}}">
                <small class="hidden">Forgot Password?</small>
              </a>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
              <input
                type="password"
                class="form-control form-control-merge"
                id="login-password"
                name="login-password"
                tabindex="2"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="login-password"
              />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
          </div>
          <div class="mb-1">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember-me" tabindex="3" />
              <span class="form-check-label">{{ __('Remember me') }}</span>
              
            </div>
          </div>

          <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                  
                </a>
            @endif

          <x-jet-button class="btn btn-danger w-100 btn-session">
            {{ __('Iniciar Sesion') }}
        </x-jet-button>
        
        </form>

       
      </div>
    </div>
    <!-- /Login basic -->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/auth-login.js'))}}"></script>
@endsection
