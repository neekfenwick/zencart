<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$CHARSET"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<base href="{{ $BASE_HREF }}"/>

<style>
{!! $EMAIL_COMMON_CSS !!}
</style>

</head>

<body id="{{ $MODULE_NAME }}">
<div class="holder">

  <!-- Header Section -->
  <div class="header">
    <img src="{{ $EMAIL_LOGO_FILE }}" alt="{{ $EMAIL_LOGO_ALT_TEXT }}" title="{{ $EMAIL_LOGO_ALT_TEXT }}" width="{{ $EMAIL_LOGO_WIDTH }}" height="{{ $EMAIL_LOGO_HEIGHT }}" border="0"/>
    {!! $EXTRA_HEADER_INFO !!}
  </div>