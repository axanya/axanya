@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
@if($type == 'welcome')
Welcome to {{$site_name}}!<!--  In order to get started, you need to confirm your email address. -->
@elseif($type == 'change')
To complete the process of changing your email address, you must confirm your new address below:
@else
Confirm your email address:
@endif
</div>
  <div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
    <a target="_blank" style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;border:1px solid;display:block;padding:10px 16px;text-decoration:none;border-radius:2px;text-align:center;vertical-align:middle;font-weight:bold;white-space:nowrap;background:#ffffff;border-color:#ff5a5f;background-color:#ff5a5f;color:#ffffff;border-top-width:1px" href="{{ $url.('users/confirm_email?code='.$token) }}">
      Confirm Email
    </a>
</div>
@stop