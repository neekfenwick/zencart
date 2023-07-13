@include ('common_header')
  <!-- Content Section -->
  <div class="content">
    <div><table class="context">
@foreach ($CONTEXT as $row)
<tr><td class="label">{!! $row['label'] !!}</td><td class="value">
@if (gettype($row['value']) == 'array')
  {!! $row['value']['html'] !!}
@else
  {!! $row['value'] !!}
@endif
</td></tr>
@endforeach
</table>
</div>
    <hr>
    <div>{!! nl2br($EMAIL_MESSAGE) !!}</div>
    <hr>
  </div>
@include ('common_footer')