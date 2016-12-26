@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" ng-controller="reports">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reports
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
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
              <h3 class="box-title">Reports Form</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-1 control-label">From</label>
                  <div class="col-sm-2">
                  <input type="text" ng-model="from" ng-change="report(from, to, category)" class="form-control date" placeholder="From Date">
                  </div>
                  <label class="col-sm-1 control-label">To</label>
                  <div class="col-sm-2">
                  <input type="text" ng-model="to" ng-change="report(from, to, category)" class="form-control date" placeholder="To Date">
                  </div>
                  <label class="col-sm-1 control-label">Category</label>
                  <div class="col-sm-2">
                  <select class="form-control" ng-model="category" ng-change="report(from, to, category)">
                    <option value="">Users</option>
                    <option value="rooms">Rooms</option>
                    <option value="reservations">Reservations</option>
                  </select>
                  </div>
                </div>
            </div>
            <div class="box-body print_area" id="users" ng-show="users_report.length">
            <div class="text-center"><h4>Users Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Registered At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in users_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.first_name }}</td>
                    <td>@{{ item.last_name }}</td>
                    <td>@{{ item.email }}</td>
                    <td>@{{ item.status }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div>
            <div class="box-body print_area" id="rooms" ng-show="rooms_report.length">
            <div class="text-center"><h4>Rooms Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Name</th>
                  <th>Host Name</th>
                  <th>Property Type</th>
                  <th>Room Type</th>
                  <th>Status</th>
                  <th>Created At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in rooms_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.name }}</td>
                    <td>@{{ item.host_name }}</td>
                    <td>@{{ item.property_type_name }}</td>
                    <td>@{{ item.room_type_name }}</td>
                    <td>@{{ item.status }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div>
            <div class="box-body print_area" id="reservations" ng-show="reservations_report.length">
            <div class="text-center"><h4>Reservations Report (@{{ from }} - @{{ to }})</h4></div>
              <table class="table">
                <thead>
                  <th>Id</th>
                  <th>Host Name</th>
                  <th>Guest Name</th>
                  <th>Room Name</th>
                  <th>Total Amount</th>
                  <th>Status</th>
                  <th>Created At</th>
                </thead>
                <tbody>
                  <tr ng-repeat="item in reservations_report">
                    <td>@{{ item.id }}</td>
                    <td>@{{ item.host_name }}</td>
                    <td>@{{ item.guest_name }}</td>
                    <td>@{{ item.room_name }}</td>
                    <td><span ng-bind-html="item.total_amount"></span></td>
                    <td>@{{ item.status }}</td>
                    <td>@{{ item.created_at }}</td>
                  </tr>
                </tbody>
              </table>
              <br>
            </div>
            <div class="text-center" id="print_footer" ng-show="users_report.length || rooms_report.length || reservations_report.length">
              <a class="btn btn-success" id="export" href="{{ url('admin/reports/export') }}/@{{ from }}/@{{ to }}/@{{ (category) ? category : 'users' }}"><i class="fa fa-file-excel-o"></i> Export</a>
              <button class="btn btn-info" ng-click="print(category)"><i class="fa fa-print"></i> Print</button>
            </div>
            <br>
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
  <style type="text/css">
  @media print {
  body * {
    visibility: hidden;
  }
  .print_area * {
    visibility: visible;
  }
  .print_area {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
@stop