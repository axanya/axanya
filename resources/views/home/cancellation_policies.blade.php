@extends('template')
      
@section('main')
<main id="site-content" role="main">

<div class="page-container page-container-responsive space-top-4 space-4">
  <h1 class="row-space-2">Cancellation Policies</h1>

  <p class="text-lead">
    {{ $site_name }} allows hosts to choose among three standardized cancellation policies  (Flexible, Moderate, and Strict) that we will enforce to protect both guest and host alike. The Super Strict cancellation policies apply to special circumstances and are by invitation only. The Long Term cancellation policy applies to all reservations of 28 nights or more. Each listing and reservation on our site will clearly state the cancellation policy. Guests may cancel and review any penalties by viewing their travel plans and then clicking 'Cancel' on the appropriate reservation.
  </p>

  <div class="panel">
    <ul class="panel-header tabs tabs-header" role="tablist" data-permalink="true">
      <li>
        <a href="#flexible" aria-controls="flexible" aria-selected="true" class="tab-item" role="tab">Flexible</a>
      </li>
      <li>
        <a href="#moderate" aria-controls="moderate" aria-selected="false" class="tab-item" role="tab">Moderate</a>
      </li>
      <li>
        <a href="#strict" aria-controls="strict" aria-selected="false" class="tab-item" role="tab">Strict</a>
      </li>
      <li>
        <a href="#super-strict-30" aria-controls="super-strict-30" aria-selected="false" class="tab-item" role="tab">Super Strict 30 Days</a>
      </li>
      <li>
        <a href="#super-strict-60" aria-controls="super-strict-60" aria-selected="false" class="tab-item" role="tab">Super Strict 60 Days</a>
      </li>
      <li>
        <a href="#long-term" aria-controls="long-term" aria-selected="false" class="tab-item" role="tab">Long Term</a>
      </li>
    </ul>

    <div id="flexible" class="panel-body tab-panel" role="tabpanel" aria-hidden="false">
      

  <h3>Flexible: Full refund 1 day prior to arrival, except fees</h3>
  <ul>
    <li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">
      <div class="col-md-4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            1 day prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label">
              Thu, Jul 16
              <br>3:00 PM
            </div>
        </div>
      </div>

    <div class="col-md-4 timeline-segment timeline-segment-partly-refundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>For a full refund, cancellation must be made a full 24 hours prior to listing’s local check in time (or 3:00 PM if not specified) on the day of check in.  For example, if check-in is on Friday, cancel by Thursday of that week before check in time.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest cancels less than 24 hours before check-in, the first night is non-refundable.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest arrives and decides to leave early, the nights not spent 24 hours after the official cancellation are 100% refunded.</p>
      </div>
</div>



<br>

    </div>
    <div id="moderate" class="panel-body tab-panel" role="tabpanel" aria-hidden="true">
      

  <h3>Moderate: Full refund 5 days prior to arrival, except fees</h3>
  <ul>
    <li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">
      <div class="col-md-4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            5 days prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label">
              Sun, Jul 12
              <br>3:00 PM
            </div>
        </div>
      </div>

    <div class="col-md-4 timeline-segment timeline-segment-partly-refundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>For a full refund, cancellation must be made five full days prior to listing’s local check in time (or 3:00 PM if not specified) on the day of check in.  For example, if check-in is on Friday, cancel by the previous Sunday before check in time.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest cancels less than 5 days in advance, the first night is non-refundable but the remaining nights will be 50% refunded.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest arrives and decides to leave early, the nights not spent 24 hours after the cancellation occurs are 50% refunded.</p>
      </div>
</div>



<br>

    </div>
    <div id="strict" class="panel-body tab-panel" role="tabpanel" aria-hidden="true">
      

  <h3>Strict: 50% refund up until 1 week prior to arrival, except fees</h3>
  <ul>
    <li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">
      <div class="col-md-4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            7 days prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label">
              Fri, Jul 10
              <br>3:00 PM
            </div>
        </div>
      </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>For a 50% refund, cancellation must be made seven full days prior to listing’s local check in time (or 3:00 PM if not specified) on the day of check in, otherwise no refund. For example, if check-in is on Friday, cancel by Friday of the previous week before check in time.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest cancels less than 7 days in advance, the nights not spent are not refunded.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest arrives and decides to leave early, the nights not spent are not refunded.</p>
      </div>
</div>



<br>

    </div>
    <div id="super-strict-30" class="panel-body tab-panel" role="tabpanel" aria-hidden="true">
      

  <h3>Super Strict 30 Days: 50% refund up until 30 days prior to arrival, except fees</h3>
  <ul>
    <li>Note: The Super Strict cancellation policy applies to special circumstances and is by invitation only.</li>
<li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">
      <div class="col-md-4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            30 days prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label">
              Wed, Jun 17
              <br>3:00 PM
            </div>
        </div>
      </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>For a 50% refund, cancellation must be made 30 full days prior to listing’s local check in time (or 3:00 PM if not specified) on the day of check in.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest cancels less than 30 days in advance, the nights not spent are not refunded.</p>
      </div>
      <div class="col-md-4">
          <p>If guest arrives and decides to leave early, the nights not spent are not refunded.</p>
      </div>
</div>



<br>

    </div>
    <div id="super-strict-60" class="panel-body tab-panel" role="tabpanel" aria-hidden="true">
      

  <h3>Super Strict 60 Days: 50% refund up until 60 days prior to arrival, except fees</h3>
  <ul>
    <li>Note: The Super Strict cancellation policy applies to special circumstances and is by invitation only.</li>
<li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">
      <div class="col-md-4 timeline-segment-refundable timeline-segment">
        <div class="timeline-point">
          <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
            60 days prior
          </div>

          <div class="timeline-point-marker"></div>
            <div class="timeline-point-label">
              Mon, May 18
              <br>3:00 PM
            </div>
        </div>
      </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>For a 50% refund, cancellation must be made 60 full days prior to listing’s local check in time (or 3:00 PM if not specified) on the day of check in.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest cancels less than 60 days in advance, the nights not spent are not refunded.</p>
      </div>
      <div class="col-md-4">
          <p>If guest arrives and decides to leave early, the nights not spent are not refunded.</p>
      </div>
</div>



<br>

    </div>
    <div id="long-term" class="panel-body tab-panel" role="tabpanel" aria-hidden="true">
      

  <h3>Long Term: First month down payment, 30 day notice for lease termination</h3>
  <ul>
    <li>Note: The Long Term cancellation policy applies to all reservations of 28 nights or more.</li>
<li>Cleaning fees are always refunded if the guest did not check in.</li>
<li>The {{ $site_name }} service fee is non-refundable.</li>
<li>If there is a complaint from either party, notice must be given to {{ $site_name }} within 24 hours of check-in.</li>
<li>{{ $site_name }} will mediate when necessary, and has the final say in all disputes.</li>
<li>A reservation is officially canceled when the guest clicks the cancellation button on the cancellation confirmation page, which they can find in Dashboard &gt; Your Trips &gt; Change or Cancel.</li>
<li>Cancellation policies may be superseded by the Guest Refund Policy, safety cancellations, or extenuating circumstances. Please review these exceptions.</li>
<li>Applicable taxes will be retained and remitted.</li>
  </ul>

	<div class="timeline-container hide-sm">
  <div class="row clearfix">

    <div class="col-md-4 timeline-segment timeline-segment-partly-refundable">
      <div id="second-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check in
        </div>
        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Fri, Jul 17
            <br>3:00 PM</div>
      </div>
    </div>

    <div class="col-md-4 timeline-segment timeline-segment-nonrefundable">
      <div id="third-point" class="timeline-point">
        <div class="tooltip tooltip-bottom-middle dark-caret-bottom-middle tooltip-fixed timeline-point-tooltip">
          Check out
        </div>

        <div class="timeline-point-marker"></div>
          <div class="timeline-point-label">Mon, Jul 20
            <br>11:00 AM</div>
      </div>
    </div>
  </div>

  <div class="timeline-fineprint">
      Example
  </div>
</div>

<div class="row clearfix">
      <div class="col-md-4">
          <p>If the guest books a long term stay and decides to cancel the long term agreement before the start date, the first payment is paid to the host in full.</p>
      </div>
      <div class="col-md-4">
          <p>If the guest books a long term stay and decides to cancel the long term agreement during their stay, the guest must use the online alteration tool in order to agree to a new checkout date.  Regardless of the checkout date chosen, the guest is required to pay the host for the 30 days following the cancellation date, or up to the end date of the guest’s original reservation if the remaining days of the original reservation is less than 30 days.</p>
      </div>
</div>

<br>

    </div>
  </div>
</div>
</main>
@endsection

@push('scripts')
<script type="text/javascript">
  $('.tab-item').click(function()
  {
    $('.tab-item').each(function()
    {
      $($(this).attr('href')).hide();
      $(this).attr('aria-selected', 'false');
    });
    $($(this).attr('href')).show();
    $(this).attr('aria-selected', 'true');
  });
</script>
@stop
