@extends('admin.template')

@section('main')
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Coupon Code
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Coupon Code</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Coupon Code Management</h3>
              <div style="float:right;"><a class="btn btn-success" href="{{ url('admin/add_coupon_code') }}">Add Coupon Code</a></div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
{!! $dataTable->table() !!}
</div>
</div>
</div>
</div>
</section>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ url('css/buttons.dataTables.css') }}">
<script src="{{ url('js/dataTables.buttons.js') }}"></script>
<script src="{{ url('js/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!}
@stop
