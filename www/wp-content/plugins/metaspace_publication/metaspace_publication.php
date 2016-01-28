<?php
/**
 * @package metaspace publication
 * @version 1.0
 */
/*
Plugin Name: metaspace publication
Plugin URI: 
Description: 
Author: Hoodoo
Version: 1.0
Author URI: http://hoodoothis.loc/
*/
   
define('IMPROVE_UPLOAD_PUBLICATION', WP_CONTENT_DIR .'/uploads/publication/');
define('IMPROVE_UPLOAD_PUBLICATION_URL', wp_upload_dir()['baseurl'].'/publication/');

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

function translit($cyr_str) {
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
    metaspace_publication_store_post();        
}


function get_template_part_with_data($slug, array $data = array()){
    extract($data);
    require locate_template($slug);
}


add_action('admin_menu', 'metaspace_publication_admin');


function metaspace_publication_admin() {
       
    add_menu_page('Publications', 'Publications', 8, 'publication', 'metaspace_publication_admin_edit', '', 4);
   
}

function metaspace_publication_store_post() {
    

    global $wpdb;
    
        
    $table_publication = $wpdb -> prefix.publication;
    
    // Добавление / редактирование публикации   
    if(isset($_POST['metaspace_publication_edit_publication_form'])) {
        
        $flag_new_publication = false;

        
        if(function_exists('current_user_can') && !current_user_can('manage_options')) {
            
            die( _e('Hacker?'));
           
        }
        
        if(function_exists('check_admin_referer')) {
            check_admin_referer('improve_base_setup_form');
        }
        
        $post_date = $_POST['date_year'].'-'.$_POST['date_month'].'-'.$_POST['date_day']; //$_POST['date'];
        $post_anons = $_POST['anons'];
        $post_authors = $_POST['authors'];
        $post_source = $_POST['source'];
        $post_link = $_POST['link'];
        $post_blocked = $_POST['blocked'];
        
        if(!empty($post_link) && strpos($post_link, "http://") === false) {
            $post_link = 'http://'.$post_link;
        }
                
        if(empty($_POST['publication_id']))
        {
            $result = $wpdb -> insert($table_publication,
                            array('ctime' => current_time('mysql', 1)),
                            array('%s'));
                       
            $publication_id = $wpdb->insert_id;

            $flag_new_publication = true;
        } 
        else {
            $publication_id = (int)$_POST['publication_id'];
        }
        
               
        if(!$publication_id) {
            $_SESSION['publication_message'] = array('error' => 1, 'message' => 'Publication can not added');  
            
            wp_redirect('/wp-admin/admin.php?page=publication');
            exit();
        }
        else {
            $publication_data = metaspace_publication_get_by_id($publication_id);
        
            if(isset($_POST['del_file'])) {
                @unlink(IMPROVE_UPLOAD_PUBLICATION.$publication_data['file']);
                $wpdb->update($table_publication, array('file' => ''), array( 'id' => $publication_id ), array('%s'), array( '%d' ));
            }                
            
            if(isset($_FILES['file']) && $_FILES['file']["error"] == UPLOAD_ERR_OK)
            {
                if($publication_data['file']) {
                     @unlink(IMPROVE_UPLOAD_PUBLICATION.$publication_data['file']);
                }

                $new_filename = $publication_id."_".translit(basename($_FILES['file']['name']));

                if(move_uploaded_file($_FILES['file']["tmp_name"], IMPROVE_UPLOAD_PUBLICATION.$new_filename)) {
                    $wpdb->update($table_publication, array('file' => $new_filename), array( 'id' => $publication_id ), array('%s'), array( '%d' ));
                }
            } 

            $wpdb->update( 
                    $table_publication, 
                    array( 
                            'date' => $post_date, 
                            'anons' => $post_anons, 
                            'authors' => $post_authors,                            
                            'source' => $post_source, 
                            'link' => $post_link, 
                            'blocked' => ($post_blocked ? 1 : 0),  
                    ), 
                    array( 'id' => $publication_id ), 
                    array('%s', '%s', '%s', '%s', '%s', '%d'),
                    array( '%d' ) 
            );
            
            
        }
        
        if($flag_new_publication) {
            wp_redirect('/wp-admin/admin.php?page=publication');
            $_SESSION['publication_message'] = array('error' => 0, 'message' => 'Publication added');
        }        
        else {
            wp_redirect('/wp-admin/admin.php?page=publication&action=edit&id='.$publication_id);
            $_SESSION['publication_message'] = array('error' => 1, 'message' => 'Publication updated');            
        }
        
       
        exit();
        
    }          

    //Удаление публикации
    if($_GET['action'] == 'delete' && !empty($_GET['id'])) {
        $id = (int)$_GET['id'];

        $result = $wpdb->query($wpdb->prepare("DELETE FROM $table_publication WHERE id = %d", $id));
        
        if($result)
            $_SESSION['publication_message'] = array('error' => 0, 'message' => 'Publication deleted');
        else
            $_SESSION['publication_message'] = array('error' => 0, 'message' => 'The publication was not removed');

        wp_redirect('/wp-admin/admin.php?page=publication');
        exit();

    }
    
}

/*
 * Страница публикаций
 * 
 */

function metaspace_publication_admin_edit() {
        
    global $wpdb;
    
    if(isset($_SESSION['publication_message'])) {
        echo "<h3>".$_SESSION['publication_message']['message']."</h3>";
        unset($_SESSION['publication_message']);
    }
    
    $action = $_GET['action'];
                
    $table_publication = $wpdb -> prefix.publication;
    
    if($action == 'add' || $action == 'edit')
    {    
        if($action == 'edit') {
            $title = 'Edit';
            $publication_data = metaspace_publication_get_by_id($_GET['id']);
        }
        else {
            $title = 'Add';
        }

        echo '<div class="wrap"><h1>'.$title.' publication<a class="page-title-action" href="?page=publication">Return</a></h1>';
  
        
        echo "<form method='post' action='". $_SERVER['PHP_SELF']."?page=publication' enctype='multipart/form-data' >
              <input type='hidden' name='metaspace_publication_edit_publication_form' value='1'>
              <input type='hidden' name='publication_id' value='".$publication_data['id']."'>";

        if(function_exists('wp_nonce_field')) {
            wp_nonce_field('improve_base_setup_form');
        }
        
        ob_start();
        wp_editor(@$publication_data['anons'], 'wpeditortext', array('textarea_name' => 'anons', 'textarea_rows' => '15') );        
        $text = ob_get_contents();        
        ob_end_clean();
        
        ob_start();
        wp_editor(@$publication_data['authors'], 'wpeditorauthors', array('textarea_name' => 'authors', 'textarea_rows' => '15') );        
        $authors = ob_get_contents();        
        ob_end_clean();
         
        $date = metaspace_publication_dateform('date_', @$publication_data['year'], @$publication_data['month'], @$publication_data['day'], '2015', 2);
        
        echo "<table width='80%'>
            <tr>
                <td width='10%' valign='top'>Date:</td>
                <td>".$date."</td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Anons:</td>
                <td>".$text."</td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Authors:</td>
                <td>".$authors."</td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Source:</td>
                <td><input style='width:100%' type='text' name='source' value='".esc_textarea(@$publication_data['source'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>Link:</td>
                <td><input style='width:100%' type='text' name='link' value='".esc_textarea(@$publication_data['link'])."'></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
                <td valign='top'>File:</td>
                <td><input type='file' name='file' id='file'  multiple='false' />
                ".(!empty($publication_data['file']) ? '<br><a href="'.IMPROVE_UPLOAD_PUBLICATION_URL.$publication_data['file'].'" target="_blank">'.$publication_data['file'].'</a><br> delete file: <input type="checkbox" name="del_file" value=1>' : '')."
                </td>
                <td style='color:#666666'><i></i></td>
            </tr>            
            <tr>
                <td valign='top'>Blocked:</td>
                <td><input type='checkbox' name='blocked' ".(@$publication_data['blocked'] ? 'checked="checked"' : '')."></td>
                <td style='color:#666666'><i></i></td>
            </tr>
            <tr>
            <td align='right' colspan=3><input style='width:140px; height:25px' type='submit' value='Save'></td>
            </tr>
            </table> 
            </form>
            ";            
    }     
    else {
        
        $publications_list = $wpdb -> get_results("SELECT *, DATE_FORMAT(`date`, '%d.%m.%Y') as `f_date`
                                                FROM $table_publication  ORDER BY `date` DESC", ARRAY_A);
         
        echo '<div class="wrap"><h1>Publications<a class="page-title-action" href="?page=publication&action=add">Add publication</a></h1>';
          
        echo "<table class = 'wp-list-table widefat fixed striped posts'>
              <thead>
              <tr>
                    <td width='50px'>#</td>
                    <td width='100px'>Date</td>
                    <td width='200px' class = 'manage-column column-title column-primary sortable desc'>Anons</td>      
                    <td>Source</td>    
                    <td>Link</td>                  
                    <td>Blocked</td>
                    <td></td>
              </tr>
              </thead>
              <tbody>";  
        
        
        foreach($publications_list as $v) {
             echo "<tr>
                    <td class = 'ow-title'>".$v['id']."</td>
                    <td class = 'ow-title'>".$v['f_date']."</td>
                    <td class = 'ow-title'>".$v['anons']."</td>
                    <td class = 'ow-title'>".$v['source']."</td>
                    <td class = 'ow-title'>".$v['link']."</td>
                    <td class = 'ow-title'>".($v['blocked'] ? 'yes' : 'no')."</td>               
                    <td class = 'ow-title'><a href='?page=publication&action=edit&id=".$v['id']."'>edit</a>&nbsp;&nbsp;<a href='?page=publication&action=delete&id=".$v['id']."' onclick='return confirm(\"Are you sure want to delete the publication ".$v['anons']."?\")'>delete</a></td>
                  </tr>"; 
        }
        
        echo "</tbody>             
              </table>";
    }
}




function metaspace_publication_get_by_id($id) {
    global $wpdb;
    
    if(empty($id))
        return false;
    
    $table_publication = $wpdb -> prefix.publication;
       
    $result = $wpdb->get_row($wpdb->prepare( "SELECT *, YEAR(`date`) as `year`, MONTH(`date`) as `month`, DAY(`date`) as `day` FROM $table_publication WHERE id = %d", $id), ARRAY_A); 
    
    return $result;
}


function metaspace_publication_get_all_public() {
    global $wpdb;

    $table_publication = $wpdb -> prefix.publication;
       
    $result = $wpdb -> get_results("SELECT * FROM $table_publication WHERE `blocked` = 0 ORDER BY `date` DESC", ARRAY_A);

    return $result;
}

function metaspace_publication_get_all() {
    global $wpdb;

    $table_publication = $wpdb -> prefix.publication;
       
    $result = $wpdb -> get_results("SELECT * FROM $table_publication ORDER BY `date` DESC", ARRAY_A);

    return $result;
}

 

function metaspace_publication_dateform($pref, $year, $month, $day, $start_year = '1990', $step = 1, $not_year = false, $type=false)
{
    # Объявление глобальных переменных
    $arrMonth = array("January" , "Febrary" , "March" , "April" , "May" , "June" , "July" , "August" , "September" , "October", "November", "December" );

    if(!$year)
      {
        $year	= date('Y');
            $month	= date('m');
            $day	= date('d');
      }

    # Вывод списка дней месяца
    $ret = "<select name=".$pref."day class='text day' onchange='date".$type."change(this.value)'>";
    for($i = 1; $i < 32; $i++)
      {
        $SEL = ($i == $day ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . ">" . $i . "</option>\n";
      }

    # Вывод списка месяцев года
    $ret .= "</select><select name=".$pref."month class='text month' onchange='month".$type."change(this.options[selectedIndex].text)'>";
    for($i = 1; $i < 13; $i++)
      {
        $SEL= ($i == $month ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . " >" . $arrMonth[ $i - 1 ] . "</option>\n";
      }


    # Вывод списка выбора года
    $ret .= "</select>";

     if(!$not_year)
      {
    $ret .= "<select name=".$pref."year class='text year'>";
    for($i = $start_year; $i < (date('Y') + $step); $i++)
      {
        $SEL = ($i == $year ? 'SELECTED' : '');
            $ret .= "<option " . $SEL . " value=" . $i . ">" . $i . "</option>\n";
      }

    $ret .= "</select>\n";
    }
    return $ret;
}

?>
