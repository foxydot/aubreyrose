<?php
add_action('gform_post_process','msdlab_process_to_tickets', 1, 3);

if(!isset($_GET['wootickets_process'])){
    if(isset($_POST['gform_submit'])){
        if(empty($_POST['gform_submit'])){
            add_action('template_redirect','msdlab_process_to_tickets', 1, 3);
        }
    }
}
function msdlab_process_to_tickets($form, $page_number = 1, $source_page_number = 1){
    $is_valid = TRUE;
    if(class_exists('GFFormDisplay')){
        $submission = GFFormDisplay::$submission;
        foreach($submission AS $sub){
            if(!$sub['is_valid']){
                $is_valid = FALSE;
            }
        }
    }
    if($is_valid && isset($_POST['my_form_action'])){
        $ret = '<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript">
            function closethisasap() {
                document.forms["redirectpost"].submit();
            }
        </script>
    </head>
    <body onload="closethisasap();">
    <form name="redirectpost" method="post" action="'.$_POST['my_form_action'].'">';
        unset($_POST['my_form_action']);
        if ( !is_null($_POST) ) {
            foreach ($_POST as $k => $v) {
                if(is_array($v)){
                    foreach($v as $a => $b){
                        $ret .= '<input type="hidden" name="' . $k . '['.$a.']" value="' . $b . '"> ';
                    }
                }else{
                    $ret .= '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
                }
            }
        }
        $ret .= '
    </form>
    </body>
    </html>';
    print $ret;
    exit;
    }
}




function tix_redirect_post($url, array $data)
{
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript">
            function closethisasap() {
                document.forms["redirectpost"].submit();
            }
        </script>
    </head>
    <body onload="closethisasap();">
    <form name="redirectpost" method="post" action="<? echo $url; ?>">
        <?
        if ( !is_null($data) ) {
            foreach ($data as $k => $v) {
                echo '<input type="hidden" name="' . $k . '" value="' . $v . '"> ';
            }
        }
        ?>
    </form>
    </body>
    </html>
    <?
    exit;
}