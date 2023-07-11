@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div>{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},</div>
    <br>
    <div>{!! $EMAIL_MESSAGE_HTML !!}</div>
@if (!empty($GV_ANNOUNCE))
    <div class="gv-block">{!! $GV_ANNOUNCE !!}
      <br>
      {!! $GV_REDEEM_HTML !!}
    </div>
@endif
  </div>
@include ('common_footer')