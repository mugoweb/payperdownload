{* Override for order e-mail template to show download links. *}
{set-block scope=root variable=subject}{"Order"|i18n("design/standard/shop")}: {$order.order_nr}{/set-block}

{"Order"|i18n("design/standard/shop")}: {$order.order_nr}

{"Customer"|i18n("design/standard/shop")}:

{shop_account_view_gui view=ascii order=$order}


{"Product items"|i18n("design/standard/shop")}

{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

{foreach $order.product_items as $item}
{$item.item_count}x {$item.object_name} {$item.price_inc_vat|l10n( 'currency', $locale, $symbol )}: {$item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
{def $download_classes = ezini('PayPerDownloadSettings', 'ContentClasses', 'payperdownload.ini')}
{if $download_classes|contains($item.item_object.contentobject.class_identifier)}
    {def $download_attributes = ezini('PayPerDownloadSettings', 'Attributes', 'payperdownload.ini')}
    {def $download_attribute = $download_attributes[$item.item_object.contentobject.class_identifier]}

    - Download: {concat('http://', ezini('SiteSettings', 'SiteURL', 'site.ini'), '/content/download/',$item.item_object.contentobject.data_map.$download_attribute.content.id,'/',$item.item_object.contentobject.data_map.$download_attribute.content.data_map.file.id)|ezurl('no')}
    {undef $download_attributes}
    {undef $download_attribute}
{/if}

{/foreach}

{"Subtotal of items"|i18n("design/standard/shop")}:  {$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
{$OrderItem:item.description}: 	{$OrderItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}
{/section}

{"Order total"|i18n("design/standard/shop")}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}

{undef $currency $locale $symbol}