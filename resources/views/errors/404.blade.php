@extends('layouts.master')
@section('content')
<div class="error-page">
        <h2 class="headline text-info"> 404</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Página no encontrada.</h3>

          <p>
            No encontramos lo que buscabas.
            Tal vez desee <a href="{{url('home')}}"> Volver al inicio</a>
          </p>
        </div>
        <!-- /.error-content -->
      </div>
@endsection
@section('scripts')
<script>
  $(document).ready(function(e){
    swal.closeModal();
  });
</script>
@endsection