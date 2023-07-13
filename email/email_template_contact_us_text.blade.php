@include ('common_header_text')
@foreach ($CONTEXT as $row)
{!! $row['label'] !!}:	@if (gettype($row['value']) == 'array')
{!! $row['value']['text'] !!}
@else
{!! $row['value'] !!}
@endif
@endforeach

----------------------------------------------

{!! strip_tags($EMAIL_MESSAGE) !!}

----------------------------------------------

@include ('common_footer_text')