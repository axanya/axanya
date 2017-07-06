@extends('emails.template')

@section('emails.main')
<div style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;margin-top:1em">
  <div style="margin-bottom: 12px;">Please use the code below to confirm your email.<div>
  <div style="font-size: 24px; margin-bottom: 20px; color: #ff5a5f;">{{ $otp }}</div>
</div>
@stop
