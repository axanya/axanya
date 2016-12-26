@extends('emails.template')

@section('emails.main')
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
      We've issued you a payout of {{ $result['currency']['symbol'] }}{{ $payout_amount }} via PayPal.
      This payout should arrive in your account, taking into consideration weekends and holidays.
</div>
<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:1em">
  
      <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border-spacing:0;line-height:150%;width:100%;width:100%;table-layout:fixed">
        <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:33%;text-align:left;padding:0 10px 10px 0">
            Date
          </th>
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;text-align:left;padding:0 10px 10px 0;display:block">
            Detail
          </th>
          <th style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:25%;word-wrap:break-word;white-space:nowrap;text-align:right;padding:0 10px 10px 0">
            Amount
          </th>
        </tr>
          <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:33%;text-align:left;padding:0 10px 10px 0">
              {{ $result['checkin_mdy'] }} - {{ $result['checkout_mdy'] }}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:100%;text-align:left;padding:0 10px 10px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              {{ $result['code'] }} - {{ $full_name }} - {{ $result['rooms']['name'] }}
            </td>
            <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;width:25%;word-wrap:break-word;text-align:right;padding:0 10px 10px 0;white-space:nowrap">
              {{ $result['currency']['symbol'] }}{{ $payout_amount }}
            </td>
          </tr>
      </tbody></table>

</div>
<br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif"><br style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
    You can view the status of your payouts in your <a href="{{ $url.('users/transaction_history') }}" style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;color:#ff5a5f;text-decoration:none" target="_blank">transaction history</a>.
      If you have any questions, please contact us.
</div>
@stop