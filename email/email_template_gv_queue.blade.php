@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div class="gv">{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!}</div>
    <div class="gv">{!! $GV_NOTICE_HEADER !!}</div>
    <div class="gv">{!! $GV_NOTICE_RELEASED !!}</div>
    <div class="gv">{!! $GV_NOTICE_AMOUNT_REDEEM !!}</div>
    <div class="gv">{!! $GV_NOTICE_THANKS !!}</div>

@if (!empty($TEXT_REDEEM_GV_MESSAGE_BODY))
    <div>{!! $TEXT_REDEEM_GV_MESSAGE_BODY !!}</div>
@endif
@if (!empty($TEXT_REDEEM_GV_MESSAGE_FOOTER))
    <div>{!! $TEXT_REDEEM_GV_MESSAGE_FOOTER !!}</div>
@endif
  </div>

@include ('common_footer')