<?php
global $woocommerce,$woocommerce_gravityforms; 
//add_filter('woocommerce_add_cart_item_data', 'msdlab_test', 10, 2);
add_filter('woocommerce_add_cart_item_data', array($woocommerce_gravityforms, 'add_cart_item_data'), 10, 2);
add_filter('woocommerce_add_to_cart_validation', array($woocommerce_gravityforms, 'add_to_cart_validation'), 99, 3);
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

function msdlab_test($cart_item_meta, $product_id){
    ts_data($_POST);
    die();
}

class MsdlabTribeWooTickets extends TribeWooTickets {
    public function process_front_end_tickets_form() {
        global $woocommerce;

        if ( empty( $_GET['wootickets_process'] ) || intval( $_GET['wootickets_process'] ) !== 1 || empty( $_POST['product_id'] ) )
            return;
        
        foreach ( (array) $_POST['product_id'] as $product_id ) {
            $quantity = isset( $_POST['quantity_' . $product_id] ) ? intval( $_POST['quantity_' . $product_id] ) : 0;
            
            $passed_validation  = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );        
                ts_data($product_id);
            if ( $quantity > 0 && $passed_validation)
                $woocommerce->cart->add_to_cart( $product_id, $quantity );
        }
    }
}
