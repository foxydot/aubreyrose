<?php
//add_action('genesis_footer','msdlab_show_actions');
function msdlab_show_actions(){
    global $wp_filter;
    $tag = $_GET['tag'];
    if($tag){
        ts_var( $wp_filter[$tag] );
    }
    //ts_var($wp_filter['wp']);
}

/*
* A useful troubleshooting function. Displays arrays in an easy to follow format in a textarea.
*/
if(!function_exists('ts_data')){
	function ts_data($data){
		$ret = '<textarea class="troubleshoot" rows="20" cols="100">';
		$ret .= print_r($data,true);
		$ret .= '</textarea>';
		print $ret;
	}
}
/*
* A useful troubleshooting function. Dumps variable info in an easy to follow format in a textarea.
*/
if(!function_exists('ts_var')){
	function ts_var($var){
		ts_data(var_export( $var , true ));
	}
}

//add_action('after_setup_theme','msdlab_get_posted_data');
function msdlab_get_posted_data(){
    ts_data($_POST);
}
