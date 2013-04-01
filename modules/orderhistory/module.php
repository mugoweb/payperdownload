<?php

$Module  = array( 'name' => 'Order History' );

$ViewList = array();

$ViewList['view'] = array(
          'functions' => array( 'view' ),
          'params'    => array ( 'user_id'),
          'script'    => 'view.php'
);

$FunctionList = array();
$FunctionList['view'] = array();

?>