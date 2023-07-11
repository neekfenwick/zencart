@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div class="content-line">{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},</div>
    <div class="content-line">{!! $EMAIL_MESSAGE_HTML !!}</div>
    <div class="content-line">{!! $COUPON_TEXT_TO_REDEEM !!}</div>
    <div class="content-line">{!! $COUPON_TEXT_VOUCHER_IS !!}
      <span class="coupon-code">{!! $COUPON_CODE !!}</span> -
      <b>{!! $COUPON_HELP !!}</b>.</div>
@if (!empty($coupon_is_valid_for_sales))
    <div class="content-line">{!! $TEXT_COUPON_IS_VALID_FOR_SALES_EMAIL !!}</div>
@else
    <div class="content-line">{!! TEXT_NO_COUPON_IS_VALID_FOR_SALES_EMAIL !!}</div>
@endif
@if (!empty($coupon_product_count))
    <div class="content-line">{!! $TEXT_COUPON_PRODUCT_COUNT_PER_PRODUCT !!}</div>
@else
    <div class="content-line">{!! TEXT_COUPON_PRODUCT_COUNT_PER_ORDER !!}</div>
@endif
    <span class="coupon-desc">{!! $COUPON_DESCRIPTION !!}</span>
  </div>
  <div class="content-line">{!! $COUPON_TEXT_REMEMBER !!}</div>
  <div class="content-line">{!! $COUPON_REDEEM_STORENAME_URL !!}</div>
  </div>
@include ('common_footer')