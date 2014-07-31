<?php
/*
 * DodatkiJoomla.pl
 * Wszelkie prawa zastrzeżone.
 */

if (!class_exists('vmShopperPlugin'))
{
    require( JPATH_VM_PLUGINS . DS . 'vmshopperplugin.php' );
}

/**
 * Plugin class allows to override default Virtuemart 2
 *
 * @author Dodatkijoomla.pl
 */
class plgVmShopperVm2_numery_zamowien extends vmShopperPlugin
{
    /**
     * Required abstract vmShopperPlugin method.
     *
     * @param int $_orderID Order id
     *
     * @return string|void
     */
    public function plgVmOnUpdateOrderBEShopper( $_orderID )
    {

    }

    /**
     * Method fired on 'plgVmOnUserOrder' trigger.
     *
     * @param array $orderData Array with order data
     *
     * @return array
     */
    function plgVmOnUserOrder( $orderData )
    {
        // New order number is now next id in the orders table
        $db = JFactory::getDBO();
        $query =
            '
                SELECT
                    virtuemart_order_id + 1
                FROM
                    #__virtuemart_orders
                ORDER BY
                    virtuemart_order_id DESC
                LIMIT
                    1';
        $db->setQuery($query);
        $last_id = $db->loadResult();

        // zero-pad numbers for example - 000035
        if( $this->params->get( 'zero_pad', 0 ) == 1 )
        {
            $last_id = str_pad( $last_id, 6, "0", STR_PAD_LEFT );
        }

        $orderData->order_number = $last_id;

        return $orderData;
    }

}

?>