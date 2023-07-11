@include ('common_header_text')

{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},

{!! $EMAIL_MESSAGE_TEXT !!}

@if (!empty($GV_ANNOUNCE))
{!! $GV_ANNOUNCE !!}

{!! $GV_REDEEM_TEXT !!}

@endif
@include ('common_footer_text')