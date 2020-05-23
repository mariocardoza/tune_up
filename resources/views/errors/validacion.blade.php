@if (count($errors) > 0)
    @foreach ($errors->all() as $message)
      <?php
         echo ("<script type='text/javascript'>toastr.error('". $message ."');</script>");
      ?>
    @endforeach
@endif
