@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div><img src="email/gv.gif" width="100" height="80"><span class="gv-amount">{!! $EMAIL_GV_AMOUNT !!}</span> - <span class="gv-code">{!! $GV_REDEEM_CODE !!}</span></div>
    <div class="store-name">{!! $EMAIL_STORE_NAME !!}</div>
    <div class="content-line">{!! $EMAIL_GV_TEXT_HEADER !!}</div>
    <div class="content-line">{!! $EMAIL_GV_FROM !!}
@if (!empty($EMAIL_GV_MESSAGE))
      {!! $EMAIL_GV_MESSAGE !!}</div>

@if (!empty($EMAIL_GV_SEND_TO))
    <div class="content-line"><tt>{!! $EMAIL_GV_SEND_TO !!}</tt></div>
@endif
    <div class="content-line"><tt>{!! nl2br($EMAIL_MESSAGE) !!}</tt>
@endif
</div>
    <hr/>
    <div class="content-line">{!! $GV_REDEEM_HOW !!}<br>{!! $GV_REDEEM_URL !!}</div>
@if (!empty($EMAIL_ADVISORY))
    <div class="content-line">{!! $EMAIL_ADVISORY !!}</div>
@endif
    <div class="content-line">{!! $EMAIL_GV_FIXED_FOOTER !!}</div>
    <div class="content-line">{!! $EMAIL_GV_SHOP_FOOTER !!}</div>
  </div>
@include ('common_footer')