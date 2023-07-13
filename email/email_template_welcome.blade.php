@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div class="content-line">{!! $EMAIL_GREETING !!}</div>
    <div class="content-line">{!! nl2br($EMAIL_WELCOME) !!}</div>
@if (!empty($COUPON_TEXT_VOUCHER_IS) && !empty($COUPON_TEXT_TO_REDEEM))
    <div class="coupon-block">{!! $COUPON_TEXT_VOUCHER_IS !!} {!! $COUPON_DESCRIPTION !!}
      <br>
      {!! $COUPON_TEXT_TO_REDEEM !!}
      <span class="coupon-code">{!! $COUPON_CODE !!}</span>
      <br>
      {!! $COUPON_HELP !!}
    </div>
@endif
@if (!empty($GV_WORTH))
    <div class="gv-block">{!! $GV_WORTH !!}
      <br>
      {!! nl2br($GV_REDEEM) !!}
      <br>
      {!! $GV_LINK_HTML !!}
      <br>
      {!! $GV_LINK_OTHER !!}
    </div>
@endif
<!-- end gv/coupon section -->
    <div class="content-line">{!! $EMAIL_MESSAGE !!}</div>
    <div class="content-line">{!! $EMAIL_CONTACT_OWNER !!}
      <br>
      {!! nl2br($EMAIL_CLOSURE) !!}</div>
  </div>
</div>
@include ('common_footer')