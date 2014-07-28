<?php
class msd_woo_donate{
    
    var $donate_id;
    
    function msd_woo_donate(){
        $this->__construct();
    }
    
    function __construct(){
        add_action('woocommerce_cart_contents',array(&$this,'msd_woocommerce_after_cart_table'));
        add_action('init',array(&$this,'msd_process_donation'));
        add_filter('woocommerce_get_price', array(&$this,'msd_get_price'),10,2);
        $donate_id = $this->msd_get_donation_id('donate');
    }
    
    function msd_get_donation_id($slug){
        
    }
    
    function msd_donation_exsits(){
        global $woocommerce;
        if( sizeof($woocommerce->cart->get_cart()) > 0){
            foreach($woocommerce->cart->get_cart() as $cart_item_key => $values){
                $_product = $values['data'];
                if($_product->id == $donate_id)
                    return true;
            }
        }
        return false;
    }
    
    function msd_round_donation($total, $value = 10){
         $donation = (ceil($total / $value) * $value) - $total;
         return $donation;
    }
    
    function msd_woocommerce_after_cart_table(){
     
        global $woocommerce;
        $donate = isset($woocommerce->session->msd_donation) ? floatval($woocommerce->session->msd_donation) : 0;
     
        if(!$this->msd_donation_exsits()){
            unset($woocommerce->session->msd_donation);
        }
     
        // uncomment the next line of code if you wish to round up the order total with the donation e.g. £53 = £7 donation
        // $donate = msd_round_donation($woocommerce->cart->total );
     
        if(!$this->msd_donation_exsits()){
            ?>
            <tr class="donation-block">
                <td colspan="6">
                    <div class="donation">
                        <p class="message"><strong>Add a donation to your order:</strong></p>
                        <form action=""method="post">
                            <div class="input text">
                                <label>Donation (&pound;):</label>
                                <input type="text" name="jc-donation" value="<?php echo $donate;?>"/>
                            </div>
                            <div class="submit">
                                <input type="submit" name="donate-btn" value="Add Donation"/>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            <?php
        }
    }

    function msd_process_donation(){
        global$woocommerce;
        $donation = isset($_POST['jc-donation']) && !empty($_POST['jc-donation']) ? floatval($_POST['jc-donation']) : false;
        if($donation && isset($_POST['donate-btn'])){
            // add item to basket
            $found = false;
            // add to session
            if($donation >= 0){
                $woocommerce->session->msd_donation = $donation;
                //check if product already in cart
                if( sizeof($woocommerce->cart->get_cart()) > 0){
                     foreach($woocommerce->cart->get_cart() as $cart_item_key=>$values){
                        $_product = $values['data'];
                         if($_product->id == $donate_id)
                            $found = true;
                    }
     
                    // if product not found, add it
                    if(!$found)
                        $woocommerce->cart->add_to_cart($donate_id);
                }else{
                    // if no products in cart, add it
                    $woocommerce->cart->add_to_cart($donate_id);
                }
            }
        }
    }
    
    function msd_get_price($price, $product){
        global $woocommerce;
        if($product->id == $donate_id){
            return isset($woocommerce->session->msd_donation) ? floatval($woocommerce->session->msd_donation) : 0;
        }
        return $price;
    }

}
$msd_woo_donate = new msd_woo_donate;
?>