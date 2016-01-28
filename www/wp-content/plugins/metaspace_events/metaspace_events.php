<?php
/**
 * @package metaspace events
 * @version 1.0
 */
/*
Plugin Name: metaspace events
Plugin URI: 
Description: 
Author: Hoodoo
Version: 1.0
Author URI: http://hoodoothis.loc/
*/
   
define('IMPROVE_UPLOAD_EVENTS', WP_CONTENT_DIR .'/uploads/events/');
define('IMPROVE_UPLOAD_EVENTS_URL', wp_upload_dir()['baseurl'].'/events/');

if(!session_id()) {        
    session_start();
}

$tr = array(
    "Г"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I",
    "і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
    "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
    "Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
    "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
    "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
    "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
    "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
    "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
    "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
    "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
    "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
    "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
    "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"'",
    "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
);

function events_translit($cyr_str) {
    global $tr;
    $file = strtr($cyr_str,$tr);
    $file = str_replace( ' ', '_', $file );
    return $file;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/pluggable.php');

if(empty($_POST) && !empty($_GET['action'])) {
    $_POST['action'] = $_GET['action'];
}

if(!empty($_POST)) {
    metaspace_events_store_post();        
}

add_action('admin_menu', 'metaspace_events_admin');


function metaspace_events_admin() {
       
    add_menu_page('Events', 'Events', 8, 'events', 'metaspace_events_admin_edit', '', 4);
   
}

function metaspace_events_store_post() {
    

    global $wpdb;
    
        
    $table_events = $wpdb -> prefix.events;
    
    // Добавление / редактирование публикации   
    if(isset($_POST['metaspace_events_edit_events_form'])) {
        
        $flag_new_events = false;

        
        if(function_exists('current_user_can') && !current_user_can('manage_options')) {
            
            die( _e('Hacker?'));
           
        }
        
        if(function_exists('check_admin_referer')) {
            check_admin_referer('improve_base_setup_form');
        }
        
        $post_s_date = $_POST['s_date_year'].'-'.$_POST['s_date_month'].'-'.$_POST['s_date_day'];
        
        if(!empty($_POST['f_date_year']) && !empty($_POST['f_date_month']) && !empty($_POST['f_date_day']))
            $post_f_date = $_POST['f_date_year'].'-'.$_POST['f_date_month'].'-'.$_POST['f_date_day']; 
        else
            $post_f_date = null;
        
        $post_name = $_POST['name'];
        $post_anons = $_POST['anons'];
        $post_place = $_POST['place'];
        $post_place_link = $_POST['place_link'];
        $post_link = $_POST['link'];
        $post_blocked = $_POST['blocked'];
        $post_is_past_events = $_POST['is_past_events'];
        
        if(!empty($post_link) && strpos($post_link, "http://") === false) {
            $post_link = 'http://'.$post_link;
        }
        
        if(!empty($post_place_link) && strpos($post_place_link, "http://") === false) {
            $post_place_link = 'http://'.$post_place_link;
        }
                
        if(empty($_POST['events_id']))
        {
            $result = $wpdb -> insert($table_events,
                            array('ctime' => current_time('mysql', 1)),
                            array('%s'));
                       
            $events_id = $wpdb->insert_id;

            $flag_new_events = true;
        } 
        else {
            $events_id = (int)$_POST['events_id'];
        }
        
               
        if(!$events_id) {
            $_SESSION['events_message'] = array('error' => 1, 'message' => 'Events can not added');  
            
            wp_redirect('/wp-admin/admin.php?page=events');
            exit();
        }
        else {
            $events_data = metaspace_events_get_by_id($events_id);
        
            if(isset($_POST['del_file'])) {
                @unlink(IMPROVE_UPLOAD_EVENTS.$events_data['file']);
                $wpdb->update($table_events, array('file' => ''), array( 'id' => $events_id ), array('%s'), array( '%d' ));
            }                
            
            if(isset($_FILES['file']) && $_FILES['file']["error"] == UPLOAD_ERR_OK)
            {
                if($events_data['file']) {
                     @unlink(IMPROVE_UPLOAD_EVENTS.$events_data['file']);
                }

                $new_filename = $events_id."_".events_translit(basename($_FILES['file']['name']));

                if(move_uploaded_file($_FILES['file']["tmp_name"], IMPROVE_UPLOAD_EVENTS.$new_filename)) {
                    $wpdb->update($table_events, array('file' => $new_filename), array( 'id' => $events_id ), array('%s'), array( '%d' ));
                }
            } 
            
            if(isset($_POST['del_preview'])) {
                @unlink(IMPROVE_UPLOAD_EVENTS.$events_data['preview']);
                $wpdb->update($table_events, array('preview' => ''), array( 'id' => $events_id ), array('%s'), array( '%d' ));
            }                
            
            if(isset($_FILES['preview']) && $_FILES['preview']["error"] == UPLOAD_ERR_OK)
            {
                if($events_data['preview']) {
                     @unlink(IMPROVE_UPLOAD_EVENTS.$events_data['preview']);
                }

                $new_filename = $events_id."_preview_".events_translit(basename($_FILES['preview']['name']));

                include_once 'metaspace_images.php';
  
                resizeImage($_FILES['preview']["tmp_name"], IMPROVE_UPLOAD_EVENTS.$new_filename, $_FILES['preview']['type'], CFG_IMAGE_RESIZE_TYPE_6, 361, 242);
  
                $wpdb->update($table_events, array('preview' => $new_filename), array( 'id' => $events_id ), array('%s'), array( '%d' ));
               
            } 
                             
            $wpdb->update( 
                    $table_events, 
                    array( 
                            's_date' => $post_s_date, 
                            'f_date' => $post_f_date, 
                            'name' => $post_name, 
                            'anons' => $post_anons,                            
                            'place' => $post_place, 
                            'place_link' => $post_place_link, 
                            'link' => $post_link, 
                            'blocked' => ($post_blocked ? 1 : 0),
                            'is_past_events' => ($post_is_past_events ? 1 : 0),
                    ), 
                    array( 'id' => $events_id ), 
                    array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d'),
                    array( '%d' ) 
            );

            
        }
        
        if($flag_new_events) {
            wp_redirect('/wp-admin/admin.php?page=events');
            $_SESSION['events_message'] = array('error' => 0, 'message' => 'Event added');
        }        
        else {
            wp_redirect('/wp-admin/admin.php?page=events&action=edit&id='.$events_id);
            $_SESSION['events_message'] = array('error' => 1, 'message' => 'Event updates');            
        }
        
       
        exit();
        
    }          

    //Удаление публикации
    if($_GET['action'] == 'delete' && !empty($_GET['id'])) {
        $id = (int)$_GET['id'];

        $result = $wpdb->query($wpdb->prepare("DELETE FROM $table_events WHERE id = %d", $id));
        
        if($result)
            $_SESSION['events_message'] = array('error' => 0, 'message' => 'Event deleted');
        else
            $_SESSION['events_message'] = array('error' => 0, 'message' => 'The event was not removed');

        wp_redirect('/wp-admin/admin.php?page=events');
        exit();

    }
    
}

/*
 * Страница публикаций
 * 
 */

function metaspace_events_admin_edit() {
        
    global $wpdb;
    
    if(isset($_SESSION['events_message'])) {
        echo "<h3>".$_SESSION['events_message']['message']."</h3>";
        unset($_SESSION['events_message']);
    }
    
    $action = $_GET['action'];
                
    $table_events = $wpdb -> prefix.events;
    
    if($action == 'add' || $action == 'edit')
    {    
        if($action == 'edit') {
            $title = 'Edit';
            $events_data = metaspace_events_get_by_id($_GET['id']);
        }
        else {
            $title = 'Add';
        }

        echo '<div class="wrap"><h1>'.$title.' event<a class="page-title-action" href="?page=events">Return</a></h1>';
  
        
        echo "<form method='post' action='". $_SERVER['PHP_SELF']."?page=events' enctype='multipart/form-data' >
              <input type='hidden' name='metaspace_events_edit_events_form' value='1'>
              <input type='hidden' name='events_id' value='".$events_data['id']."'>";

        if(function_exists('wp_nonce_field')) {
            wp_nonce_field('improve_base_setup_form');
        }
        
        ob_start();
        wp_editor(@$events_data['anons'], 'wpeditortext', array('textarea_name' => 'anons', 'textarea_rows' => '15') );        
        $text = ob_get_contents();        
        ob_end_clean();

        $s_date = metaspace_events_dateform('s_date_', @$events_data['s_year'], @$events_data['s_month'], @$events_data['s_day'], '2015', 2);
        $f_date = metaspace_events_dateform('f_date_', @$events_data['f_year'], @$events_data['f_month'], @$events_data['f_day'], '2015', 2, false, true);

        echo "<table width='80%'>
            <tr>
                <td width='10%' valign='top'>Date begin:</td>
                <td>".$s_date."</td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td width='10%' valign='top'>Date end:</td>
                <td>".$f_date."</td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Name:</td>
                <td><input style='width:100%' type='text' name='name' value='".esc_textarea(@$events_data['name'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Anons:</td>
                <td>".$text."</td>
                <td style='color:#666666'><i></i></td>
            </tr> 
            <tr>
                <td valign='top'>Place:</td>
                <td><input style='width:100%' type='text' name='place' value='".esc_textarea(@$events_data['place'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Link on place:</td>
                <td><input style='width:100%' type='text' name='place_link' value='".esc_textarea(@$events_data['place_link'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Link on site:</td>
                <td><input style='width:100%' type='text' name='link' value='".esc_textarea(@$events_data['link'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Preview:</td>
                <td><input type='file' name='preview' id='preview'  multiple='false' />
                ".(!empty($events_data['preview']) ? '<br><a href="'.IMPROVE_UPLOAD_EVENTS_URL.$events_data['preview'].'" target="_blank"><img src="'.IMPROVE_UPLOAD_EVENTS_URL.$events_data['preview'].'" width="150"></a><br> delete preview: <input type="checkbox" name="del_preview" value=1>' : '')."
                </td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>File:</td>
                <td><input type='file' name='file' id='file'  multiple='false' />
                ".(!empty($events_data['file']) ? '<br><a href="'.IMPROVE_UPLOAD_EVENTS_URL.$events_data['file'].'" target="_blank">'.$events_data['file'].'</a><br> delete file: <input type="checkbox" name="del_file" value=1>' : '')."
                </td>
                <td style='color:#666666'><i></i></td>
            </tr>            
            <tr>
                <td valign='top'>Blocked:</td>
                <td><input type='checkbox' name='blocked' ".(@$events_data['blocked'] ? 'checked="checked"' : '')."></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
            <td align='right' colspan=3><input style='width:140px; height:25px' type='submit' value='Save'></td>
            </tr>
            </table> 
            </form>
            ";     
        
        /*         
          <tr>
                <td valign='top'>In Past events:</td>
                <td><input type='checkbox' name='is_past_events' ".(@$events_data['is_past_events'] ? 'checked="checked"' : '')."></td>
                <td style='color:#666666'><i></i></td>
            </tr> 
         */
    }     
    else {
        
        $eventss_list = $wpdb -> get_results("SELECT *, DATE_FORMAT(`s_date`, '%d.%m.%Y') as `fs_date`,
                                               DATE_FORMAT(`f_date`, '%d.%m.%Y') as `fe_date` FROM $table_events  ORDER BY `s_date` DESC", ARRAY_A);
         
        echo '<div class="wrap"><h1>Events<a class="page-title-action" href="?page=events&action=add">Add event</a></h1>';
          
        echo "<table class = 'wp-list-table widefat fixed striped posts'>
              <thead>
              <tr>
                    <td width='50px'>#</td>
                    <td width='100px'>Preview</td>
                    <td width='100px'>Dates</td>
                    <td width='200px' class = 'manage-column column-title column-primary sortable desc'>Event</td>      
                    <td>Place</td>    
                    <td>Link on site</td>         
                    <td>Blocked</td>
                    <td></td>
              </tr>
              </thead>
              <tbody>";  
        
        //           <td>In past events</td>
       
        foreach($eventss_list as $v) {
             echo "<tr>
                    <td class = 'ow-title'>".$v['id']."</td>
                    <td class = 'ow-title'>".(!empty($v['preview']) ? "<a href='".IMPROVE_UPLOAD_EVENTS_URL.$v['preview']."' target='_blank'><img src='".IMPROVE_UPLOAD_EVENTS_URL.$v['preview']."' width='100'></a>" : '')."</td>
                    <td class = 'ow-title'>".$v['fs_date'].( (!empty($v['f_date']) && $v['f_date'] != '0000-00-00') ? ' - '.$v['fe_date'] : '')."</td>
                    <td class = 'ow-title'>".$v['name']."</td>
                    <td class = 'ow-title'><a href='".$v['place_link']."'>".$v['place']."</a></td>
                    <td class = 'ow-title'>".$v['link']."</td>
                    <td class = 'ow-title'>".($v['blocked'] ? 'yes' : 'no')."</td>           
                    <td class = 'ow-title'><a href='?page=events&action=edit&id=".$v['id']."'>edit</a>&nbsp;&nbsp;<a href='?page=events&action=delete&id=".$v['id']."' onclick='return confirm(\"Are you sure want to delete the event ".$v['anons']."?\")'>delete</a></td>
                  </tr>"; 
             
             //<td class = 'ow-title'>".($v['is_past_events'] ? 'yes' : 'no')."</td>  
        }
        
        echo "</tbody>             
              </table>";
    }
}




function metaspace_events_get_by_id($id) {
    global $wpdb;
    
    if(empty($id))
        return false;
    
    $table_events = $wpdb -> prefix.events;
       
    $result = $wpdb->get_row($wpdb->prepare( "SELECT *, YEAR(`s_date`) as `s_year`, MONTH(`s_date`) as `s_month`, DAY(`s_date`) as `s_day`,
                                                YEAR(`f_date`) as `f_year`, MONTH(`f_date`) as `f_month`, DAY(`f_date`) as `f_day`        
                                                FROM $table_events WHERE id = %d", $id), ARRAY_A); 
    
    return $result;
}


function metaspace_events_get_all_public($type = false) {
    global $wpdb;

    $table_events = $wpdb -> prefix.events;
       
    if($type) { //прошедшие
        $result = $wpdb -> get_results("SELECT *, DATE_FORMAT(`s_date`, '%d.%m.%Y') as `fs_date`,
                                        DATE_FORMAT(`f_date`, '%d.%m.%Y') as `fe_date` 
                                        FROM $table_events WHERE `blocked` = 0 AND
                                        ( (`f_date` IS NOT NULL AND `f_date` < DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (`f_date` IS NULL AND `s_date` < DATE_FORMAT(NOW(), '%Y-%m-%d'))) 
                                        ORDER BY `s_date` DESC", ARRAY_A);
    }
    else { //будущие
        $result = $wpdb -> get_results("SELECT *, DATE_FORMAT(`s_date`, '%d.%m.%Y') as `fs_date`,
                                        DATE_FORMAT(`f_date`, '%d.%m.%Y') as `fe_date` 
                                        FROM $table_events WHERE `blocked` = 0  AND
                                        ( (`f_date` IS NOT NULL AND `f_date` >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (`f_date` IS NULL AND `s_date` >= DATE_FORMAT(NOW(), '%Y-%m-%d'))) 
                                        ORDER BY `s_date` DESC", ARRAY_A);
    }

    return $result;
}

function metaspace_events_get_all() {
    global $wpdb;

    $table_events = $wpdb -> prefix.events;
       
    $result = $wpdb -> get_results("SELECT *, DATE_FORMAT(`s_date`, '%d.%m.%Y') as `fs_date`,
                                               DATE_FORMAT(`f_date`, '%d.%m.%Y') as `fe_date` FROM $table_events ORDER BY `s_date` DESC", ARRAY_A);

    return $result;
}

 

function metaspace_events_dateform($pref, $year, $month, $day, $start_year = '1990', $step = 1, $not_year = false, $type=false)
{

    # Объявление глобальных переменных
    $arrMonth = array("January" , "Febrary" , "March" , "April" , "May" , "June" , "July" , "August" , "September" , "October", "November", "December" );

    if(!$year && !$type)
      {
        $year	= date('Y');
        $month	= date('m');
        $day	= date('d');
      }

    # Вывод списка дней месяца
    $ret = "<select name=".$pref."day class='text day' onchange='date".$type."change(this.value)'>";
    
    if($type)
            $ret .= "<option value=''>- day -</option>\n";
    
    for($i = 1; $i < 32; $i++)
      {
       
        
        $SEL = (($i == $day) ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . ">" . $i . "</option>\n";
      }

    # Вывод списка месяцев года
    $ret .= "</select><select name=".$pref."month class='text month' onchange='month".$type."change(this.options[selectedIndex].text)'>";
    
    if($type)
            $ret .= "<option value=''>- month -</option>\n";
    
    for($i = 1; $i < 13; $i++)
      {
        $SEL= (($i == $month) ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . " >" . $arrMonth[ $i - 1 ] . "</option>\n";
      }


    # Вывод списка выбора года
    $ret .= "</select>";

     if(!$not_year)
      {
    $ret .= "<select name=".$pref."year class='text year'>";
    
    if($type)
            $ret .= "<option value=''>- year -</option>\n";
    
    for($i = $start_year; $i < (date('Y') + $step); $i++)
      {
        $SEL = (($i == $year) ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . ">" . $i . "</option>\n";
      }

    $ret .= "</select>\n";
    }
    return $ret;
}

?>
