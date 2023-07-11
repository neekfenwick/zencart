@include ('common_header_text')

{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!}

{!! $GV_NOTICE_HEADER !!}

{!! $GV_NOTICE_RELEASED !!}

{!! strip_tags($GV_NOTICE_AMOUNT_REDEEM) !!}

{!! $GV_NOTICE_THANKS !!}

@if (!empty($TEXT_REDEEM_GV_MESSAGE_BODY))
{!! $TEXT_REDEEM_GV_MESSAGE_BODY !!}

@endif
@if (!empty($TEXT_REDEEM_GV_MESSAGE_FOOTER))
{!! $TEXT_REDEEM_GV_MESSAGE_FOOTER !!}

@endif
@include ('common_footer_text')