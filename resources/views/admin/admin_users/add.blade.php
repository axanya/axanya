@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Admin User
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Admin Users</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-8 col-sm-offset-2">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Admin User Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => 'admin/add_admin_user', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_username" class="col-sm-3 control-label">Username<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('username', '', ['class' => 'form-control', 'id' => 'input_username', 'placeholder' => 'Username']) !!}
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_email" class="col-sm-3 control-label">Email<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('email', '', ['class' => 'form-control', 'id' => 'input_email', 'placeholder' => 'Email']) !!}
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_password" class="col-sm-3 control-label">Password<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('password', '', ['class' => 'form-control', 'id' => 'input_password', 'placeholder' => 'Password']) !!}
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_role" class="col-sm-3 control-label">Role<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::select('role', $roles, '', ['class' => 'form-control', 'id' => 'input_role', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('role') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), '', ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-default" name="cancel" value="cancel">Cancel</button>
                <button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
              </div>
              <!-- /.box-footer -->
            {!! Form::close() !!}
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@stop