@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Cotizaciones</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item active">Cotizaciones</li>
	    </ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-12">
            <a type="button" href="{{url('cotizaciones/create')}}" class="btn btn-success"><i class="fas fa-plus"></i> Nueva</a>
        </div>
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Cotizaciones</h3>
            
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body">
              <table class="table table-striped table-bordered" id="tablaclientes">
              	<thead>
              		<tr>
              			<th>N°</th>
              			<th>Cliente</th>
              			<th>Placa</th>
              			<th>Vehiculo</th>
              			<th></th>
              		</tr>
              	</thead>
              	<tbody>
              		@foreach($cotizaciones as $index => $c)
              			<tr>
              				<td>{{$index+1}}</td>
              				<td>{{$c->cliente->nombre}}</td>
              				<td>{{$c->vehiculo->placa}}</td>
              				<td>{{$c->vehiculo->marca->marca}}</td>
              				<td>
              					<a href="{{url('cotizaciones/'.$c->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
              				</td>
              			</tr>
              		@endforeach
              	</tbody>
              </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Form Element sizes -->
        
        <!-- /.card -->

      </div>
      <!--/.col (left) -->
      <!-- right column -->
    
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection