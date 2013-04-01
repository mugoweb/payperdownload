<?php
class PayPerDownloadType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "payperdownload";

    public function __construct()
    {
        parent::__construct( self::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'kernel/workflow/event', 'Pay Per Download' ) );
        $this->setTriggerTypes( array( 'shop' => array( 'checkout' => array( 'before' ) ) ) );
    }

    public function execute( $process, $event )
    {
        $ini = eZINI::instance( 'payperdownload.ini' );

        // Which role contains the policy for pay-per-download?
        $roleID = $ini->variable( 'PayPerDownloadSettings', 'RoleID' );
        
        // Which content class (by identifier) contains the pay-per-download file?
        $contentClasses = $ini->variable( 'PayPerDownloadSettings', 'ContentClasses' );
        
        // Which attribute (by identifier) of the above class contains the pay-per-download file?
        // Note that this is in an array because later we will pass it to fetchAttributesByIdentifier
        $attributes = $ini->variable( 'PayPerDownloadSettings', 'Attributes' );
      
        // We want the limit identifier to be by subtree
        $limitIdentifier = 'Subtree';
        
        // Get the current user
        $userID = $process->attribute( 'user_id' );
        
        // Get the order ID so that we can find out what objects there were
        $parameters = $process->attribute( 'parameter_list' );
        $orderID = $parameters['order_id'];
        
        // Get the order
        $thisOrder = eZOrder::fetch($orderID);

        // Create the role object
        $role = eZRole::fetch( $roleID );

        // Loop through each product to see whether it's relevant for role assignment
        foreach ($thisOrder->productItems() as $thisProduct)
        {
            $classIdentifier = $thisProduct["item_object"]->ContentObject->attribute( 'class_identifier' );

            // Is this in the list of downloadable products?
            if( in_array( $classIdentifier, $contentClasses ) )
            {
            
                // We have a match, so the last thing we need to do is to fetch the node of the file
                // First we want to grab the object so that we can get at its attributes
                $thisObject = $thisProduct["item_object"]->ContentObject;

                $dataMap = $thisObject->fetchAttributesByIdentifier( array( $attributes[$classIdentifier] ) );
                
                // There should only be one $dataMap item, so get the path of that
                foreach( $dataMap as $dataMapAttribute)
                {
                    $node = eZContentObjectTreeNode::fetchByContentObjectID( $dataMapAttribute->attribute( 'data_int' ) );

                    // We're only after the main node
                    $limitValue = $node[0]->attribute( 'path_string' );
                }

                // Assign the role
                $role->assignToUser( $userID, $limitIdentifier, $limitValue);
            }
        }

        // Clear the role cache
        eZRole::expireCache();

        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( PayPerDownloadType::WORKFLOW_TYPE_STRING, 'PayPerDownloadType' );
?>
