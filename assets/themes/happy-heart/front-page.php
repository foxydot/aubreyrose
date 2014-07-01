<?php
/*
Template Name: Front Page
*/
remove_all_actions('genesis_loop');
//remove sidebars (jsut in case)
remove_all_actions('genesis_sidebar');
remove_all_actions('genesis_sidebar_alt');
add_action('genesis_sidebar_alt','msdlab_homepage_sidebar_widgets');
//remove_action('genesis_entry_header', 'genesis_do_post_title');

/**
 * hero + 3 widgets
 */
//add the hero
add_action('genesis_after_header','msdlab_hero');
//add the callout
add_action('genesis_after_header','msdlab_callout');
//move footer and add three homepage widgets
remove_action('genesis_before_footer','genesis_footer_widget_areas');
add_action('genesis_after_header','msdlab_homepage_widgets');
add_action('genesis_before_footer','msdlab_homepage_callout_2');
add_action('genesis_before_footer','msdlab_homepage_footer');
add_action('genesis_before_content','msdlab_blogfeed');
/**
 * long scrollie
 */
//remove_all_actions('genesis_loop');
//add_action('genesis_loop','msd_scrollie_page');

genesis();