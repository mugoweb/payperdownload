<?php

$currentUser = eZUser::currentUser();

$currentUserID = $currentUser->attribute( 'contentobject_id' );

require_once( "kernel/common/template.php" );

$tpl = templateInit();

$Result = array();

if( $Params['user_id'] == $currentUserID ) {

    $productList = eZOrder::productList( $currentUserID , '' );
    $orderList = eZOrder::orderList( $currentUserID, '' );

    $tpl->setVariable( 'product_list', $productList );

    $tpl->setVariable( 'order_list', $orderList );

    $Result['content'] = $tpl->fetch( 'design:orderhistory/view.tpl' );
}
else {
    $tpl->setVariable( 'user_id', $currentUserID );
    $Result['content'] = $tpl->fetch('design:orderhistory/error.tpl');
}
?>