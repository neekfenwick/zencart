@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div class="content-line"><strong>{!! $EMAIL_SUBJECT !!}</strong></div>
    <div class="content-line">{!! $EMAIL_CUSTOMERS_NAME !!}</div>
    <div class="content-line">{!! nl2br($EMAIL_MESSAGE) !!}</div>
  </div>
@include ('common_footer')