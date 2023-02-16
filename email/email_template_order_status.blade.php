<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={{ $CHARSET }}"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<base href="{{ $BASE_HREF }}"/>

<style>
{{ $EMAIL_COMMON_CSS }}
</style>

</head>

<body id="order_status">
<div class="holder">

  <!-- Header Section -->
  <div class="header">
    <img src="{{ $EMAIL_LOGO_FILE }}" alt="{{ $EMAIL_LOGO_ALT_TEXT }}" title="{{ $EMAIL_LOGO_ALT_TEXT }}" width="{{ $EMAIL_LOGO_WIDTH }}" height="{{ $EMAIL_LOGO_HEIGHT }}" border="0"/>
    {{ $EXTRA_HEADER_INFO }}
  </div>


  <!-- Content Section -->
  <div class="content">
    <div>{{ $EMAIL_SALUTATION }} {{ $EMAIL_CUSTOMERS_NAME }},</div>
    <div>{{ $EMAIL_TEXT_ORDER_NUMBER }}</div>
    <div><a href="{{ $EMAIL_TEXT_INVOICE_URL }}">{{ $EMAIL_OSH_EMAIL_TEXT_INVOICE_URL }}</a></div>
    <div>{{ $EMAIL_TEXT_DATE_ORDERED }}</div>
@if ($EMAIL_TEXT_STATUS_COMMENTS)
    <div>{!! nl2br($EMAIL_TEXT_STATUS_COMMENTS) !!}<br><br></div>
@endif
    <div>{{ $EMAIL_TEXT_STATUS_UPDATED }}</div>
    <div>
@if ($ORDER_STATUS)
      <strong>{{ $EMAIL_TEXT_STATUS_LABEL}}</strong> {{ $ORDER_STATUS }}
@else
      <strong>{{ $EMAIL_TEXT_STATUS_CHANGE_OLD }}</strong> {{ $OLD_ORDER_STATUS }}.
      <strong>{{ $EMAIL_TEXT_STATUS_CHANGE_NEW }}</strong> {{ $NEW_ORDER_STATUS }}.
@endif
    </div>
    <div>{{ $EMAIL_TEXT_STATUS_PLEASE_REPLY }}</div>
    <div>{{ $EMAIL_STORE_NAME }}</div>
  </div>

  <div class="order_message">
    <div class="order_message">{{ $EMAIL_ORDER_UPDATE_MESSAGE }}</div>
  </div>


  <!-- Footer Section -->
  <div class="footer">
    <div class="copyright">{{ $EMAIL_FOOTER_COPYRIGHT }}</div>
  </div>

</div>
</body>
</html>
