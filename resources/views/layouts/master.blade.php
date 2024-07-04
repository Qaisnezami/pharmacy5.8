<?php

$setting = \App\Setting::first();
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{asset('images/logo/'.$setting->logo)}}">

    <title>{{$setting->name}} MIS</title>

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="{{asset('css/dropify.css')}}">

    <style>

.sidebar::-webkit-scrollbar {
	width: 10px;
	background-color: #3e3c3c;
}
 
.sidebar::-webkit-scrollbar-track {
  border-radius: 10px;
  background: rgba(0,0,0,0.1);
  border: 1px solid #3e3c3c;
}
 
.sidebar::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: linear-gradient(left, #fff, #e4e4e4);
  border: 1px solid #aaa;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background: #aaa;
}

.sidebar::-webkit-scrollbar-thumb:active {
  background: linear-gradient(left, #22ADD4, #1E98BA);
}


    body::-webkit-scrollbar {
	width: 10px;
	background-color: #3e3c3c;
}
 
body::-webkit-scrollbar-track {
  border-radius: 10px;
  background: rgba(0,0,0,0.1);
  border: 1px solid #3e3c3c;
}
 
body::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background: linear-gradient(left, #fff, #e4e4e4);
  border: 1px solid #aaa;
}

body::-webkit-scrollbar-thumb:hover {
  background: #aaa;
}

body::-webkit-scrollbar-thumb:active {
  background: linear-gradient(left, #22ADD4, #1E98BA);
}
    table td{
        padding: 0.3rem !important;
        font-size:14px;
    }
    .pagination{
    font-size: 10px !important;
    margin-bottom:0px !important;
    }
    
        .small-box h3 {
              font-size: 26px;
        }
        .card-header {
               border-bottom: 0px;
        }

    input{
        border-radius: 0px !important;
    }
    .modal-content{
        border-radius: 0px !important;
    }
    .select2-container{
        width: 100% !important;
    }

    @media (min-width: 1200px)
  {
    .container-xl, .container-lg, .container-md, .container-sm, .container {
          max-width: 1200px;
      }
  }
   
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">

<div class="wrapper" id="app">

    
</div>
    <!-- ./wrapper -->
<script>
    window.user = @json(auth()->user()->roles);
    window.permissions = @json(auth()->user()->permissions);
    window.username = @json(auth()->user()->name);
    window.userphoto = @json(auth()->user()->photo);
    window.asset = @json(asset('images'));
    window.lang = @json(\App::getLocale());
    window.pharmacy = @json(\App\Setting::first());
</script>

<script src="/js/app.js"></script>

<script>
    // function getFullscreenElement() {
    //     return document.fullscreenElement
    // }

    // function toggleFullscreen(){
    //     if(getFullscreenElement()){
    //         document.exitFullscreen();
    //     }else
    //     {
    //         document.documentElement.requestFullscreen().catch((e) => {
    //         console.log(e);})
    //     }
    // }
    // document.addEventListener("dblclick",() => {
    //     toggleFullscreen()
    // })
</script>

<script>
window.addEventListener("keyup", function(e){ if(e.keyCode == 27) history.back(); }, false);
</script>


</body>

</html>
