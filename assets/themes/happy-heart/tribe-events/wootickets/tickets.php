<?php 
global $woocommerce;
?>



            <?php

            $is_there_any_product         = false;
            $is_there_any_product_to_sell = false;
            $do_javascript = false;

            ob_start();
            foreach ( $tickets as $ticket ) {

                global $product;

                if ( class_exists( 'WC_Product_Simple' ) ) {
                    $product = new WC_Product_Simple( $ticket->ID );
                } else {
                    $product = new WC_Product( $ticket->ID );
                }

                $gmt_offset = ( get_option( 'gmt_offset' ) >= '0' ) ? ' +' . get_option( 'gmt_offset' ) : " " . get_option( 'gmt_offset' );
                $gmt_offset = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $gmt_offset );

                $end_date = null;
                if ( ! empty( $ticket->end_date ) ){
                    $end_date = strtotime( $ticket->end_date . $gmt_offset );
                }else{
                    $end_date = strtotime( tribe_get_end_date( get_the_ID(), false, 'Y-m-d G:i' ) . $gmt_offset );
                }

                $start_date = null;
                if ( !empty( $ticket->start_date ) )
                    $start_date = strtotime( $ticket->start_date . $gmt_offset );


                if ( ( !$product->is_in_stock() ) || ( empty( $start_date ) || time() > $start_date ) && (  empty( $end_date ) || time() < $end_date ) ) {

                    $is_there_any_product = true;

                    echo sprintf( "<input type='hidden' name='product_id[]' value='%d'>", $ticket->ID );

                    echo "<tr>";
                    echo "<td class='woocommerce'>";


                    if ( $product->is_in_stock() ) {

                        woocommerce_quantity_input( array( 'input_name'  => 'quantity_' . $ticket->ID,
                                                           'input_value' => isset($_POST['quantity_'.$ticket->ID])?$_POST['quantity_'.$ticket->ID]:0,
                                                           'min_value'   => 0,
                                                           'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(), ) );

                        $is_there_any_product_to_sell = true;

                    } else {
                        echo "<span class='tickets_nostock'>" . esc_html__( 'Out of stock!', 'tribe-wootickets' ) . "</span>";
                    }
                    echo "</td>";

                    echo "<td nowrap='nowrap' class='tickets_name'>";
                    echo $ticket->name;
                    echo "</td>";

                    echo "<td class='tickets_price'>";
                    echo $this->get_price_html( $product );
                    echo "</td>";

                    echo "<td class='tickets_description'>";
                    echo $ticket->description;
                    echo "</td>";

                    echo "</tr>";
                    
                    $gravity_form_data = get_post_meta($ticket->ID, '_gravity_form_data', true);
                    //if(do_form_for($product_id)){
                    if (is_array($gravity_form_data) && $gravity_form_data['id']) {
                        $do_javascript = true;
                        $hidden = $_POST['quantity_'.$ticket->ID]>0?'':' hidden';
                        $gform_id = $_POST['quantity_'.$ticket->ID]>0?$gravity_form_data['id']:'';
                        include_once( 'msdlab_gravityforms-product-addons-form.php' );
                        echo "<tr class='woocommerce-gravityform".$hidden."'>";
                        echo "<td class='woocommerce' colspan='4'>";
                        $product_form = new msdlab_woocommerce_gravityforms_product_form($gravity_form_data['id'], $ticket->ID);
                        $product_form->get_form($gravity_form_data);                        
                        echo "</td>";
                        echo "</tr>";                        
                    }
                }

            }
            $contents = ob_get_clean();
            if ( $is_there_any_product ) {
                $my_content = '<h2 class="tribe-events-tickets-title">'._e( 'Tickets', 'tribe-wootickets' ).'</h2>
                '.$contents;
                if ( $is_there_any_product_to_sell ) {
                    $my_content .= '
                    <tr>
                        <td colspan="4" class="woocommerce">
                            <input type="hidden" id="gform_submit" name="gform_submit" value="'.$gform_id.'" />
                            <button type="submit"
                                    class="button alt">'.translate( 'Add to cart', 'tribe-wootickets' ).'</button>
                        </td>
                    </tr>';
                }
            }
            if($do_javascript){
                $form_action = '';
            } else {
                $form_action = esc_url( add_query_arg( 'wootickets_process', 1, $woocommerce->cart->get_cart_url() ) );
            }
            
            ?>
<form action="<?php echo $form_action; ?>"
      class="cart" method="post"
      enctype='multipart/form-data'>
      <?php if($do_javascript){ ?>
           <input type="hidden" name="my_form_action" value="<?php print esc_url( add_query_arg( 'wootickets_process', 1, $woocommerce->cart->get_cart_url() ) ); ?>" >
      <?php } ?>
        <table width="100%" class="tribe-events-tickets">
            <?php echo $my_content; ?>
        </table>

</form>
<?php
if($do_javascript){
    print '<script>
        jQuery(document).ready(function($) {
            var forms = new Array;
            $(".woocommerce-gravityform").each(function(){
                var pid = $(this).prev("tr").find(".input-text.qty").attr("name");
                forms[pid] = $(this).html();
                $(this).html("");
            });
            $(".woocommerce .quantity input.qty").change(function(){
                var my_value = $(this).val();
                var my_id = $(this).attr("name");
                if( my_value > 0){
                    $(this).parent().parent().parent().next(".woocommerce-gravityform").html(forms[my_id]).removeClass("hidden");
                    $(".cart #gform_submit").val($(this).parent().parent().parent().next(".woocommerce-gravityform").find("#gform_form_id").val());
                } else {
                    $(this).parent().parent().parent().next(".woocommerce-gravityform").html("").addClass("hidden");
                }
                $(this).parent().parent().parent().next(".woocommerce-gravityform").find(".ticket-key input").val(my_value);
            });
        });
    </script>';
} ?>
