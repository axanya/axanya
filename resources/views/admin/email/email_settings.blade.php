@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Email Settings
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Email Settings</a></li>
        <li class="active">Edit</li>
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
              <h3 class="box-title">Email Settings Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/email_settings', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_driver" class="col-sm-3 control-label">Driver<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('driver', $result[0]->value, ['class' => 'form-control', 'id' => 'input_driver', 'placeholder' => 'Driver']) !!}
                    <span class="text-danger">{{ $errors->first('driver') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_host" class="col-sm-3 control-label">Host<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('host', $result[1]->value, ['class' => 'form-control', 'id' => 'input_host', 'placeholder' => 'Host']) !!}
                    <span class="text-danger">{{ $errors->first('host') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_port" class="col-sm-3 control-label">Port<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('port', $result[2]->value, ['class' => 'form-control', 'id' => 'input_port', 'placeholder' => 'Port']) !!}
                    <span class="text-danger">{{ $errors->first('port') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_from_address" class="col-sm-3 control-label">From Address<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('from_address', $result[3]->value, ['class' => 'form-control', 'id' => 'input_from_address', 'placeholder' => 'From Address']) !!}
                    <span class="text-danger">{{ $errors->first('from_address') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_from_name" class="col-sm-3 control-label">From Name<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('from_name', $result[4]->value, ['class' => 'form-control', 'id' => 'input_from_name', 'placeholder' => 'From Name']) !!}
                    <span class="text-danger">{{ $errors->first('from_name') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_encryption" class="col-sm-3 control-label">Encryption<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('encryption', $result[5]->value, ['class' => 'form-control', 'id' => 'input_encryption', 'placeholder' => 'Encryption']) !!}
                    <span class="text-danger">{{ $errors->first('encryption') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_username" class="col-sm-3 control-label">Username<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('username', $result[6]->value, ['class' => 'form-control', 'id' => 'input_username', 'placeholder' => 'Username']) !!}
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_password" class="col-sm-3 control-label">Password<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('password', $result[7]->value, ['class' => 'form-control', 'id' => 'input_password', 'placeholder' => 'Password']) !!}
                    <span class="text-danger">{{ $errors->first('password') }}</span>
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