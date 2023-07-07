@include ('common_header_text')
{!! $EMAIL_TEXT_HEADER !!}{!! $EMAIL_TEXT_FROM !!}{!! $INTRO_STORE_NAME !!}

{!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},

{!! $EMAIL_THANKS_FOR_SHOPPING !!}
{!! $EMAIL_DETAILS_FOLLOW !!}
{!! $EMAIL_SEPARATOR !!}
{!! $INTRO_ORDER_NUM_TITLE !!} {!! $INTRO_ORDER_NUMBER !!}
{!! $INTRO_DATE_TITLE !!} {!! $INTRO_DATE_ORDERED !!}
{!! $INTRO_URL_TEXT !!} {!! $INTRO_URL_VALUE !!}

@if (!empty($ORDER_COMMENTS))
{!! nl2br($ORDER_COMMENTS) !!}

@endif
{{ $PRODUCTS_TITLE }}
{{ $EMAIL_SEPARATOR }}
@foreach ($PRODUCTS_DETAIL as $product)
{{ $product['qty'] }} x {{ $product['name'] }} @if (!empty($product['model'])) {{ $product['model'] }} @endif
= {{ $product['display_price'] }}
@if ($product['onetime_charges'] != 0)
{{ $TEXT_ONETIME_CHARGES_EMAIL }} {{ $product['display_onetime_charges'] }}
@endif
@foreach ($product['attributes'] as $attr)
{{ $attr['products_options_name'] }} {{ zen_decode_specialchars($attr['value']) }}
  @if (!empty($PRODUCTS_ATTR_EXTRA[$loop->index]))
  {{ $PRODUCTS_ATTR_EXTRA[$loop->index] }}
  @endif
@endforeach
@endforeach
{{ $EMAIL_SEPARATOR }}

@foreach ($ORDER_TOTALS as $ot)
{{ strip_tags($ot['title']) }} {{ strip_tags($ot['text']) }}
@endforeach

@if ($ADDRESS_DELIVERY_DETAIL != "n/a")
{!! $ADDRESS_DELIVERY_TITLE !!}
{{ $EMAIL_SEPARATOR }}
{!! $ADDRESS_DELIVERY_DETAIL !!}
@endif
{!! $EMAIL_TEXT_TELEPHONE !!} {!! $EMAIL_CUSTOMER_PHONE !!}

{!! $ADDRESS_BILLING_TITLE !!}
{{ $EMAIL_SEPARATOR }}
{!! $ADDRESS_BILLING_DETAIL !!}

{!! $SHIPPING_METHOD_TITLE !!}
{!! $SHIPPING_METHOD_DETAIL !!}

{!! $EMAIL_TEXT_PAYMENT_METHOD !!}
{!! $EMAIL_SEPARATOR !!}
{!! $PAYMENT_METHOD_TITLE !!}

{!! $PAYMENT_METHOD_DETAIL !!}

{!! $PAYMENT_METHOD_FOOTER !!}

@if (!empty($EMAIL_ORDER_MESSAGE))
{!! $EMAIL_ORDER_MESSAGE !!}
@endif
@include ('common_footer_text')