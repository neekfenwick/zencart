@include ('html_common_header')

  <!-- Content Section -->
  <div class="content">
    <div>{!! $EMAIL_SUBJECT !!}</div>
    <div>{!! nl2br($EMAIL_MESSAGE) !!}</div>
  </div>

@include ('common_footer')