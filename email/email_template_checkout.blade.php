<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={!! $CHARSET !!}"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<base href="{!! $BASE_HREF !!}"/>

<style>
{!! $EMAIL_COMMON_CSS !!}
</style>
</head>

<body id="checkout">
<div class="holder">

  <!-- Header Section -->
  <div class="header">
    <img src="{!! $EMAIL_LOGO_FILE !!}" alt="{!! $EMAIL_LOGO_ALT_TEXT !!}" title="{!! $EMAIL_LOGO_ALT_TEXT !!}" width="{!! $EMAIL_LOGO_WIDTH !!}" height="{!! $EMAIL_LOGO_HEIGHT !!}" border="0"/>
    {!! $EXTRA_HEADER_INFO !!}
  </div>

  <!-- Content Section -->
  <div class="content">
    <div class="content-line">
      {!! nl2br($EMAIL_TEXT_HEADER) !!} <br><br>
      {!! $EMAIL_FIRST_NAME !!} {!! $EMAIL_LAST_NAME !!},<br>
      {!! $EMAIL_THANKS_FOR_SHOPPING !!}<br>
      {!! $EMAIL_DETAILS_FOLLOW !!}<br><br>

      {!! $INTRO_ORDER_NUM_TITLE !!} {!! $INTRO_ORDER_NUMBER !!}<br>
      {!! $INTRO_DATE_TITLE !!} {!! $INTRO_DATE_ORDERED !!}<br>
      <a href="{!! $INTRO_URL_VALUE !!}">{!! $INTRO_URL_TEXT !!}</a>
  </div>
  <div class="content-line"><strong>{!! $PRODUCTS_TITLE !!}</strong></div>
    <div class="order-detail-area">
      <table class="product-details" border="0" width="100%" cellspacing="0" cellpadding="2">
@foreach ($PRODUCTS_DETAIL as $product)

        <tr>
          <td class="product-details" align="right" valign="top" width="30">{{ $product['qty'] }}&nbsp;x</td>
          <td class="product-details" valign="top">
            {{ nl2br($product['name']) }}
            @if (!empty($product['model']))
            {{ nl2br($product['model']) }}
            @endif
            @if (!empty($product['attributes']))
            <nobr><small><em>
            @foreach ($product['attributes'] as $attr)
            {{ nl2br($attr['products_options_name']) }} {{ zen_decode_specialchars($attr['value']) }}
              @if (!empty($PRODUCTS_ATTR_EXTRA[$loop->index]))
              {{ $PRODUCTS_ATTR_EXTRA[$loop->index] }}
              @endif
            @endforeach
            </em></small>
            </nobr>
            @endif
          </td>
          <td class="product-details-num" valign="top" align="right">
          {{ $product['display_price'] }}
          @if ($product['onetime_charges'] != 0)
              </td></tr>
              <tr><td class="product-details">{{ nl2br($TEXT_ONETIME_CHARGES_EMAIL) }}</td>
              <td>{{ $product['display_onetime_charges'] }}
          @endif
          </td>
        </tr>

@endforeach
      </table>
    </div>
  <div class="order-detail-area">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="order-totals-text" align="right" width="100%">&nbsp;</td>
        <td class="order-totals-num" align="right" nowrap="nowrap">---------</td>
      </tr>
      @foreach ($ORDER_TOTALS as $ot)
        <tr>
          <td class="order-totals-text" align="right" width="100%">
            {{ $ot['title'] }}
          </td>
          <td class="order-totals-num" align="right" nowrap="nowrap">
            {{ $ot['text'] }}
          </td>
        </tr>
      @endforeach
    </table>
  </div>

  <div class="comments">{!! $ORDER_COMMENTS !!}</div>

 <div class="content-line"><strong>{!! $HEADING_ADDRESS_INFORMATION !!}</strong></div>
  <div class="address-block">
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
    <td class="delivery-block">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="content-line-title"><strong>{!! $ADDRESS_DELIVERY_TITLE !!}</strong></td>
      </tr>
      <tr>
        <td class="address">{!! nl2br($ADDRESS_DELIVERY_DETAIL) !!}<br>
        {!! $EMAIL_TEXT_TELEPHONE !!} {!! $EMAIL_CUSTOMER_PHONE !!}</td>
      </tr>

      <tr>
        <td class="content-line-title"><strong>{!! $SHIPPING_METHOD_TITLE !!}</strong></td>
      </tr>
      <tr>
        <td class="content-line">{!! nl2br($SHIPPING_METHOD_DETAIL) !!}</td>
      </tr>
      </table>
    </td>
    <td class="billing-block">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="content-line-title">{!! $ADDRESS_BILLING_TITLE !!}</td>
      </tr>
      <tr>
        <td class="address">{!! nl2br($ADDRESS_BILLING_DETAIL) !!}</td>
      </tr>
      <tr>
        <td class="content-line-title"><strong>{!! $PAYMENT_METHOD_TITLE !!}</strong></td>
      </tr>
      <tr>
        <td class="paymnt-detail">{!! $PAYMENT_METHOD_DETAIL !!}</td>
      </tr>
      <tr>
        <td class="payment-footer">{!! $PAYMENT_METHOD_FOOTER !!}</td>
      </tr>
      </table>
     </td>
    </tr>
  </table>
 </div>
</div>

  <div class="order_message">
    {!! $EMAIL_ORDER_MESSAGE !!}
  </div>
@include ('common_footer')