@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="help">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Help
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Help</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- right column -->
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Help Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              {!! Form::open(['url' => 'admin/add_help', 'class' => 'form-horizontal']) !!}
              <div class="box-body">
              <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="form-group">
                  <label for="input_category" class="col-sm-3 control-label">Category<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('category_id', $category->lists('name', 'id'), '', ['class' => 'form-control', 'id' => 'input_category_id', 'placeholder' => 'Select', 'ng-change' => 'change_category(category_id)', 'ng-model' => 'category_id']) !!}
                    <span class="text-danger">{{ $errors->first('category_id') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_subcategory" class="col-sm-3 control-label">Sub Category</label>
                  <div class="col-sm-6">
                    <select class="form-control" id="input_subcategory_id" name="subcategory_id">
                     <option value="">Select</option>
                     <option ng-repeat="item in subcategory" value="@{{ item.id }}">@{{ item.name }}</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('subcategory_id') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_question" class="col-sm-3 control-label">Question<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::text('question', '', ['class' => 'form-control', 'id' => 'input_question', 'placeholder' => 'Question']) !!}
                    <span class="text-danger">{{ $errors->first('question') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_answer" class="col-sm-3 control-label">Answer<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <textarea id="txtEditor" name="txtEditor"></textarea>
                    <textarea id="answer" name="answer" hidden="true">{{ old('answer') }}</textarea>
                    <span class="text-danger">{{ $errors->first('answer') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_status" class="col-sm-3 control-label">Status<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), '', ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                  </div>
                </div>
                <div class="form-group">
                  <label for="input_suggested" class="col-sm-3 control-label">Suggested</label>
                  <div class="col-sm-6">
                    {!! Form::radio('suggested', 'yes', '') !!} Yes
                    {!! Form::radio('suggested', 'no', true) !!} No
                    <span class="text-danger">{{ $errors->first('suggested') }}</span>
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

@push('scripts')
<script type="text/javascript">
$("#txtEditor").Editor(); 
$('.Editor-editor').html($('#answer').val());
</script>
@stop