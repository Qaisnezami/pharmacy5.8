<?php

$setting = \App\Setting::first();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$setting->name}} MIS | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="{{asset('images/logo/'.$setting->logo)}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/css/app.css">
  <style>
        body{
            background-image: url("{{asset('images/logo/'.$setting->background)}}")!important;
            background-size: cover !important;
        }
    </style>
</head>
<body class="hold-transition login-page" >
<div class="login-box">
  <div class="login-logo">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Enter Serial Numer</p>

      <form action="{{ route('serial.save') }}" method="post">
      @csrf
        <div class="input-group mb-3">
          <input id="email" type="text" class="form-control" name="serial"  placeholder="Serial Number" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
         
        </div>
        <div class="row">
        
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Save</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


</body>
</html>