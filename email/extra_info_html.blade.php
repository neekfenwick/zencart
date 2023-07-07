@if (!empty($EXTRA_INFO))
<div class="extra-info">
    <table class="extra-info">
        <tr><td class="extra-info-bold" colspan="2">{{ $EXTRA_INFO['OFFICE_USE'] }} </td></tr>
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_FROM'] }}</td><td>{{ $EXTRA_INFO['from'] }}</td></tr>
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_EMAIL'] }}</td><td>{{ $EXTRA_INFO['email_from'] }}</td></tr>
        @if (!empty($EXTRA_INFO['login'] ))
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_LOGIN_NAME'] }}</td><td>{{ $EXTRA_INFO['login'] }}</td></tr>
        @endif
        @if (!empty($EXTRA_INFO['login_email'] ))
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_LOGIN_EMAIL'] }}</td><td>{{ $EXTRA_INFO['login_email'] }}</td></tr>
        @endif
        @if (!empty($EXTRA_INFO['login_phone'] ))
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_LOGIN_PHONE'] }}</td><td>{{ $EXTRA_INFO['login_phone'] }}</td></tr>
        @endif
        @if (!empty($EXTRA_INFO['login_fax'] ))
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_LOGIN_FAX'] }}</td><td>{{ $EXTRA_INFO['login_fax'] }}</td></tr>
        @endif
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_IP_ADDRESS'] }}</td><td>{{ $_SESSION['customers_ip_address'] }} - {{ $_SERVER['REMOTE_ADDR'] }}</td></tr>
        @if (!empty($EXTRA_INFO['email_host_address']))
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_HOST_ADDRESS'] }}</td><td>{{ $EXTRA_INFO['email_host_address'] }}</td></tr>
        @endif
        <tr><td class="extra-info-bold">{{ $EXTRA_INFO['OFFICE_DATE_TIME'] }}</td><td>{{ $EXTRA_INFO['date'] }}</td></tr>
</div>
@endif