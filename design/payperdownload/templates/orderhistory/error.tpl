{* Error page orderview/error.tpl for when a customer is not viewing his/her own order *}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

{'You do not have access to view another user\'s order history.'|i18n('design/payperdownload')} Please view <a href={concat('orderhistory/view/', $user_id)|ezurl()}>your own</a>.

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>