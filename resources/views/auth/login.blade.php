@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="login-box ">
      <div class="login-logo">
        <a href="../../index2.html"><b>TuneUp</a>
      </div>
  <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Inicie sesión</p>

          <form method="POST" action="{{ route('login') }}">
                @csrf
            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="Nombre de usuario">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Contraseña">
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

         

          <p class="mb-1">
            <a href="forgot-password.html">¿Olvidó su contraseña?</a>
          </p>
          
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    
</div>
@endsection
