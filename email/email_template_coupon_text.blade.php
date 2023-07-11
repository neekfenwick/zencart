@include ('common_header_text')

{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},

{!! $EMAIL_MESSAGE_TEXT !!}

{!! $COUPON_TEXT_TO_REDEEM !!}

{!! $COUPON_TEXT_VOUCHER_IS !!} {!! $COUPON_CODE !!} - {!! $COUPON_DESCRIPTION !!}

{!! $COUPON_HELP !!}

@if (!empty($coupon_is_valid_for_sales))
{!! $TEXT_COUPON_IS_VALID_FOR_SALES_EMAIL !!}
@else
{!! TEXT_NO_COUPON_IS_VALID_FOR_SALES_EMAIL !!}
@endif

@if (!empty($coupon_product_count))
{!! $TEXT_COUPON_PRODUCT_COUNT_PER_PRODUCT !!}
@else
{!! TEXT_COUPON_PRODUCT_COUNT_PER_ORDER !!}
@endif

{!! $COUPON_TEXT_REMEMBER !!}

{!! $COUPON_REDEEM_STORENAME_URL !!}

@include ('common_footer_text')