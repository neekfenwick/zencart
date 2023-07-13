@include ('common_header_text')
{!! $EMAIL_GREETING !!}

{!! $EMAIL_WELCOME !!}
@if (!empty($COUPON_TEXT_VOUCHER_IS) && !empty($COUPON_TEXT_TO_REDEEM))

{!! $COUPON_TEXT_VOUCHER_IS !!}

{!! $COUPON_DESCRIPTION !!}

{!! $COUPON_TEXT_TO_REDEEM !!} {!! $COUPON_CODE !!}

{!! $COUPON_HELP !!}

{!! $EMAIL_SEPARATOR !!}
@endif
@if (!empty($GV_WORTH))

{!! $GV_WORTH !!}

{!! $GV_REDEEM !!}

{!! $GV_LINK_HTML !!}

{!! $GV_LINK_OTHER !!}

{!! $EMAIL_SEPARATOR !!}
@endif

{!! $EMAIL_MESSAGE !!}

{!! $EMAIL_CONTACT_OWNER !!}{!! $EMAIL_CLOSURE !!}

@include ('common_footer_text')