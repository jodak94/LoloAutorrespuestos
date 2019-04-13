@extends('layouts.master')

@section('content-header')
    <h1>
        Bienvenido
    </h1>
@stop

@section('styles')
  <style>
    .logo{
      position: absolute;
      right: 20px;
      bottom: 20px;
    }
    .content-wrapper {
      background-image: linear-gradient(315deg, #3c8dbc, #ecf0f5 40%);
      background-repeat: no-repeat;
      background-position: right bottom;
    }
    .resumen-container{
      position: relative;
      width: 25%;
      position: absolute;
      left: 25px;
      bottom: 20px;
    }
    .content-wrapper{
      position: relative;
    }
    .table-bordered-2 > thead > tr > th, .table-bordered-2 > tbody > tr > th, .table-bordered-2 > tfoot > tr > th, .table-bordered-2 > thead > tr > td, .table-bordered-2 > tbody > tr > td, .table-bordered-2 > tfoot > tr > td {
      border: 1px solid #b2b2b2;
    }
    .table-2{
        background-color: #e1e6ed;
    }
    .table-2>tbody>tr>td, .table-2>tbody>tr>th, .table-2>tfoot>tr>td, .table-2>tfoot>tr>th, .table-2>thead>tr>td, .table-2>thead>tr>th {
      padding: 4px;
    }
  </style>
@stop

@section('content')
    <div class="row">
      <div class="col-md-12">
        {{-- <div class="box box-primary"> --}}
        <div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-3">
                <div class="small-box" style="background-color: #3c8dbc; color: white">
                  <a href="{{route('admin.ventas.venta.index')}}" class="small-box" style="margin-bottom:0px; color:white; padding-bottom: 40px">
                    <div class="inner">
                      <h4>Ventas</h4>
                    </div>
                    <div class="icon">
                      <i class="fa fa-cart-plus"></i>
                    </div>
                  </a>
                  <a href="{{route('admin.ventas.venta.create')}}" class="small-box-footer" style="height: 25px" >
                    Crear nueva <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div class="col-md-3">
                  <div class="small-box bg-aqua">
                  <a href="{{route('admin.presupuestos.presupuesto.index')}}" class="small-box" style="margin-bottom:0px; color:white; padding-bottom: 40px">
                    <div class="inner">
                      <h4>Presupuestos</h4>
                    </div>
                    <div class="icon">
                      <i class="fa fa-pencil-square-o"></i>
                    </div>
                  </a>
                  <a href="{{route('admin.presupuestos.presupuesto.create')}}" class="small-box-footer"  >
                    Crear nuevo <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="small-box" style="background-color: #3c8dbc; color: white">
                  <a href="javascript:void(0)" class="consultarPrecio small-box" style="margin-bottom:0px; color:white; padding-bottom: 40px">
                    <div class="inner">
                      <h4>Consultar precio</h4>
                    </div>
                    <div class="icon">
                      <i class="fa fa-money"></i>
                    </div>
                  </a>
                  <a href="javascript:void(0)" class="consultarPrecio small-box-footer">
                    <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="small-box bg-aqua">
                  <a href="{{route('admin.productos.producto.index')}}" class="small-box" style="margin-bottom:0px; color:white; padding-bottom: 40px">
                    <div class="inner">
                      <h4>Productos</h4>
                    </div>
                    <div class="icon">
                      <i class="fa fa-tags"></i>
                    </div>
                  </a>
                  <a href="{{route('admin.productos.producto.create')}}" class="small-box-footer"  >
                    Crear nuevo <i class="fa fa-arrow-circle-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="resumen-container">
      <table class="table table-bordered table-bordered-2 table-2 table-hover">
        <tr><td colspan="2" style="text-align:center"><label>Resumen del día</label></td></tr>
        <tr><td><label>Ventas al Contado</label></td>
          <td>{{$total_contado}} Gs.</td>
        </tr>
        <tr><td><label>Ventas a Crédito</label></td>
          <td>{{$total_credito}}Gs.</td>
        </tr>
        <tr><td><label>Total</label></td>
          <td>{{$total}} Gs.</td>
        </tr>
      </table>
    </div>
    <img src="{{url('/images/logo1.png')}}" class="logo">

@stop
