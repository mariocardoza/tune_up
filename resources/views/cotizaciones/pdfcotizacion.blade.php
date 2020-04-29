<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cotización</title>
  <style>
    
  </style>
</head>
<body>
<header>
    <div style="font-size: 40px;">TUNE_UP</div>
    <div style="font-size: 35px;">Service</div>
    <div style="text-align: right;">Cotización    {{$cotizacion->id}}</div>
    <div>REPARACIÓN DE VEHICULOS MAQUINARIA</div>
    <div>CALLE SAN CARLOS 1004, FINAL 17 AV. NORTE</div>
    <div>TELS: 2225-4438, 2317-3535 CEL.: 7730-3565</div>
    <div>CORREO:h_rivas47@yahoo.com</div><br>
  </header>
<table width="100%" rules="all">
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
    </tr>
  </thead>
  <tbody>
   @foreach($cotizacion->repuestodetalle)

   @endforeach
  </tbody>
</table>
</body>
</html>