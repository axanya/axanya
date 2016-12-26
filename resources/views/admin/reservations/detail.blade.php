@extends('admin.template')

@section('main')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Reservation Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reservations</a></li>
        <li class="active">Details</li>
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
              <h3 class="box-title">Reservation Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => 'admin/reservation/detail/'.$result->id, 'class' => 'form-horizontal']) !!}
              <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Room name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->rooms->name }}
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->rooms->users->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Guest name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->users->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Checkin
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->checkin }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Checkout
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->checkout }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Number of guests
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->number_of_guests }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Total nights
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->nights }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Subtotal amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->subtotal }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cleaning fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->cleaning }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Additional guest fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->additional_guest }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Security fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->security }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Service fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->service }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Host fee
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->host_fee }}
                   </div>
                </div>
                @if($result->coupon_amount)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Coupon Amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->coupon_amount }}
                   </div>
                </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Total amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->total }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Currency
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency_code }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Paymode
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->paymode }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Status
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->status }}
                   </div>
                </div>
                @if($result->status == "Cancelled")
                 <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled By
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->cancelled_by }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Cancelled Date
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->cancelled_at }}
                   </div>
                </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Transaction ID
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->transaction_id }}
                   </div>
                </div>
                @if($result->paymode == 'Credit Card')
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    First name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->first_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Last name
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->last_name }}
                   </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Postal code
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->postal_code }}
                   </div>
                </div>
                @endif
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Country
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->country }}
                   </div>
                </div>
                @if(@$result->host_penalty->amount != 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Applied Penalty Amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $result->host_penalty->converted_amount }}
                   </div>
                </div>
                @endif
                @if($penalty_amount != 0)
                <div class="form-group">
                  <label class="col-sm-3 control-label">
                    Subtracted Penalty Amount
                  </label>
                  <div class="col-sm-6 col-sm-offset-1">
                    {{ $result->currency->symbol }}{{ $penalty_amount }}
                   </div>
                </div>
                @endif
              </div>
              <!-- /.box-body -->
            </form> 
              @if((($result->status == 'Accepted' &&  $result->checkin_cross == 0 ) || $result->status == 'Cancelled') && $result->check_host_payout != 'yes' && $result->admin_host_payout != 0)
                @if($result->host_payout_email_id)
                  <form action="{{ url('admin/reservation/payout') }}" method="post">
                    {!! Form::token() !!}
                    <input type="hidden" name="reservation_id" value="{{ $result->id }}">
                    <input type="hidden" name="host_payout_id" value="{{ $result->host_payout_id }}">
                    <input type="hidden" name="user_type" value="host">
                    <div class="text-center"> 
                      <button type="submit" class="btn btn-primary">Payout to Host({{ $result->currency->symbol }}{{ $result->admin_host_payout }})</button>
                    </div>
                  </form>
                @else
                  <div class="text-bold text-danger text-center">Yet, host doesn't enter his/her Payout preferences. <a href="{{ url('admin/reservation/need_payout_info/'.$result->id.'/host') }}">Send Email to Host</a></div>
                @endif
              @endif

              @if(($result->status == 'Declined' || $result->status == 'Cancelled' || $result->status == 'Expired') && $result->check_guest_payout != 'yes' && $result->admin_guest_payout != 0)
                @if($result->guest_payout_email_id)
                  <br>
                  <form action="{{ url('admin/reservation/payout') }}" method="post">
                    {!! Form::token() !!}
                    <input type="hidden" name="reservation_id" value="{{ $result->id }}">
                    <input type="hidden" name="guest_payout_id" value="{{ $result->guest_payout_id }}">
                    <input type="hidden" name="user_type" value="guest">
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Payout to Guest({{ $result->currency->symbol }}{{ $result->admin_guest_payout }})</button>
                    </div>
                  </form>
                @else
                  <div class="text-bold text-danger text-center">Yet, guest doesn't enter his/her Payout preferences. <a href="{{ url('admin/reservation/need_payout_info/'.$result->id.'/guest') }}">Send Email to Guest</a></div>
                @endif
              @endif
              
              @if($result->check_host_payout == 'yes')
                <div class="text-bold text-success text-center">Host payout amount ({{ $result->currency->symbol.$result->host_payout }}) transferred.</div>
              @endif
              @if($result->check_guest_payout == 'yes')
                <div class="text-bold text-success text-center">Guest payout amount ({{ $result->currency->symbol.$result->guest_payout }}) transferred.</div>
              @endif
              <div class="box-footer text-center">
                <a class="btn btn-default" href="{{ url('admin/reservations') }}">Back</a>
              </div>
              <!-- /.box-footer -->
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
  @push('scripts')
<script>
  $('#input_dob').datepicker({ 'format': 'dd-mm-yyyy'});
</script>
@stop
@stop