@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div>{!! $EMAIL_SALUTATION !!} {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!}</div>
    <div>{!! $EMAIL_MESSAGE_HTML !!}</div>
  </div>
@include ('common_footer')