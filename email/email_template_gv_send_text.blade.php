@include ('common_header_text')

{!! $EMAIL_GV_TEXT_HEADER !!}
{!! $EMAIL_SEPARATOR !!}

{!! $EMAIL_GV_FROM !!}
@if (!empty($EMAIL_GV_MESSAGE))
{!! $EMAIL_GV_MESSAGE !!}

@if (!empty($EMAIL_GV_SEND_TO))
{!! $EMAIL_GV_SEND_TO !!}

@endif
{!! $EMAIL_MESSAGE !!}
@endif

{!! $GV_REDEEM_HOW !!}

{!! $GV_REDEEM_URL_TEXT !!}

@if (!empty($EMAIL_ADVISORY))
{!! strip_tags($EMAIL_ADVISORY) !!}

@endif
{!! $EMAIL_GV_FIXED_FOOTER !!}
@if (!empty(EMAIL_GV_SHOP_FOOTER))

{!! $EMAIL_GV_SHOP_FOOTER !!}
@endif

@include ('common_footer_text')