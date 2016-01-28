<?php
register_nav_menu('menu', 'Основное меню');

class ThemeTopRight_Walker_Nav_Menu extends Walker_Nav_Menu
{
	function start_el(&$output, $item, $depth, $args)
	{
		$attributes = ' href="#'. str_replace(array('http://', $_SERVER['HTTP_HOST'], '/'), '', esc_attr( $item->url)) .'"';
                $attributes .= ' class="b-nav__item js-scroll-link"';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before.apply_filters( 'the_title', $item->title, $item->ID ).$args->link_after;
		$item_output .= '</a>';

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


// Отправляем сообщение с сайта на e-mail
function send_contact_form_callback() {
	    
        if(empty($_POST['contact_email'])) {
            $res = array('error' => 1, 'message' => 'E-mail can not be empty');       
        } 
        elseif(!is_email($_POST['contact_email'])) {
            $res = array('error' => 1, 'message' => 'Sorry, we are capable to annotate moleculas but failed to read not your e-mail! Please, try again.');
        }
        else {

            $to = get_option('metaspace_admin_email');
            $subject = '+1 to EMBL fans!';
            
            $body = "Someone has singed up for an alpha version. E-mail bellow!\n\n ".$_POST['contact_email']."\n";
        
            $headers[] = 'From: Metaspace <info@metaspace.com>' . "\r\n";
                                                      
            if(wp_mail( $to, $subject, $body, $headers ))
            {              
                $res = array('error' => 0, 'message' => '');
            }
            else
            {
                $res = array('error' => 1, 'message' => 'Oh, something went wrong. Don’t worry, all moleculas are safe. ');
            }
        }
        
        echo json_encode($res);

	wp_die();
}

add_action('wp_ajax_send_contact_form', 'send_contact_form_callback');
add_action('wp_ajax_nopriv_send_contact_form', 'send_contact_form_callback');