@if (!empty($EXTRA_INFO))
{{ $EXTRA_INFO['OFFICE_USE'] }}
{{ $EXTRA_INFO['OFFICE_FROM'] }}:	{{ $EXTRA_INFO['from'] }}
{{ $EXTRA_INFO['OFFICE_EMAIL'] }}:	{{ $EXTRA_INFO['email_from'] }}
@if (!empty($EXTRA_INFO['login']))
{!! $EXTRA_INFO['OFFICE_LOGIN_NAME'] !!}:	{!! $EXTRA_INFO['login'] !!}
@endif
@if (!empty($EXTRA_INFO['login_email']))
{!! $EXTRA_INFO['OFFICE_LOGIN_EMAIL'] !!}:	{!! $EXTRA_INFO['login_email'] !!}
@endif
@if (!empty($EXTRA_INFO['login_phone']))
{!! $EXTRA_INFO['OFFICE_LOGIN_PHONE'] !!}:	{!! $EXTRA_INFO['login_phone'] !!}
@endif
@if (!empty($login_fax))
{!! $EXTRA_INFO['OFFICE_LOGIN_FAX'] !!}:	{!! $EXTRA_INFO['login_fax'] !!}
@endif
{!! $EXTRA_INFO['OFFICE_IP_ADDRESS'] !!}:	{!! $_SESSION['customers_ip_address'] !!} - {!! $_SERVER['REMOTE_ADDR'] !!}
@if (!empty($EXTRA_INFO['email_host_address']))
{!! $EXTRA_INFO['OFFICE_HOST_ADDRESS'] !!}:	{!! $email_host_address !!}
@endif
{!! $EXTRA_INFO['OFFICE_DATE_TIME'] !!}:	{{ $EXTRA_INFO['date'] }}
@endif