@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
          <div class="body">
            <h2>Nguyễn Trọng Thuận</h2>
          </div>
    

           
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        h2 {
            font-size: 48px;
            color: yellow;
        }
    </style>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
