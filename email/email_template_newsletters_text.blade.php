@include ('common_header_text')

{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},

@if (empty($EMAIL_MESSAGE_TEXT))
{!! stripslashes(strip_tags(html_entity_decode($EMAIL_MESSAGE_HTML))) !!}
@else
{!! $EMAIL_MESSAGE_TEXT !!}
@endif

@include ('common_footer_text')