@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Api Credentials
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Api Credential</a></li>
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
              <h3 class="box-title">Api Credentials Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/api_credentials', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_facebook_client_id" class="col-sm-3 control-label">Facebook Client ID<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('facebook_client_id', $result[0]->value, ['class' => 'form-control', 'id' => 'input_facebook_client_id', 'placeholder' => 'Facebook Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('facebook_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_facebook_client_secret" class="col-sm-3 control-label">Facebook Client Secret<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('facebook_client_secret', $result[1]->value, ['class' => 'form-control', 'id' => 'input_facebook_client_secret', 'placeholder' => 'Facebook Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('facebook_client_secret') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_google_client_id" class="col-sm-3 control-label">Google Client ID<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('google_client_id', $result[2]->value, ['class' => 'form-control', 'id' => 'input_google_client_id', 'placeholder' => 'Google Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('google_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_google_client_secret" class="col-sm-3 control-label">Google Client Secret<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('google_client_secret', $result[3]->value, ['class' => 'form-control', 'id' => 'input_google_client_secret', 'placeholder' => 'Google Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('google_client_secret') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_google_map_key" class="col-sm-3 control-label">Google Map Key<em class="text-danger">*</em></label>

                  <div class="col-sm-6">
                    {!! Form::text('google_map_key', $result[6]->value, ['class' => 'form-control', 'id' => 'input_google_map_key', 'placeholder' => 'Google Map Key']) !!}
                    <span class="text-danger">{{ $errors->first('google_map_key') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_linkedin_client_id" class="col-sm-3 control-label">LinkedIn Client ID<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('linkedin_client_id', $result[4]->value, ['class' => 'form-control', 'id' => 'input_linkedin_client_id', 'placeholder' => 'LinkedIn Client ID']) !!}
                    <span class="text-danger">{{ $errors->first('linkedin_client_id') }}</span>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="form-group">
                  <label for="input_linkedin_client_secret" class="col-sm-3 control-label">LinkedIn Client Secret<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('linkedin_client_secret', $result[5]->value, ['class' => 'form-control', 'id' => 'input_linkedin_client_secret', 'placeholder' => 'LinkedIn Client Secret']) !!}
                    <span class="text-danger">{{ $errors->first('linkedin_client_secret') }}</span>
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