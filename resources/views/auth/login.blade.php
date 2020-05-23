@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="login-box ">
      <div class="login-logo">
        <a href="{{url('home')}}"><b>Tune Up</a>
      </div>
  <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Inicie sesión</p>
          @if(Session::has('error'))
          <div class="alert alert-danger alert-dismissable" role="alert">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              {{ Session::get('error') }}
          </div>
      @endif
      @if(Session::has('mensaje'))
          <div class="alert alert-success alert-dismissable" role="alert">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              {{ Session::get('mensaje') }}
          </div>
      @endif
          <form method="POST" action="{{ route('authenticate') }}">
                @csrf
            <div class="input-group mb-3">
              <input type="text" name="username" required="" class="form-control" placeholder="Nombre de usuario">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>

            <div class="input-group mb-3">
              <input type="password" name="password" required="" class="form-control" placeholder="Contraseña">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              
              <!-- /.col -->
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

         
          
          <!--p class="mb-1">
            <a href="{{url('password/reset')}}">¿Olvidó su contraseña?</a>
          </p-->
           
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    
</div>
@endsection
