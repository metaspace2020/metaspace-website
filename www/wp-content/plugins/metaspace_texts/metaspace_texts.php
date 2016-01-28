<?php
/**
 * @package metaspace texts
 * @version 1.0
 */
/*
Plugin Name: metaspace texts
Plugin URI: 
Description: 
Author: Hoodoo
Version: 1.0
Author URI: http://hoodoothis.loc/
*/

function metaspace_get_tweets() {

    $token = get_option('metaspace_twitter_token');
    $token_secret = get_option('metaspace_twitter_token_secret');
    $consumer_key = get_option('metaspace_twitter_key');
    $consumer_secret = get_option('metaspace_twitter_key_secret');
    
    $host = 'api.twitter.com';
    $method = 'GET';
    $path = '/1.1/statuses/user_timeline.json'; // api call path

    $query = array( // query parameters
        'screen_name' => get_option('metaspace_twitter_screen_name'),
        'count' => get_option('metaspace_twitter_count')
    );

    $oauth = array(
        'oauth_consumer_key' => $consumer_key,
        'oauth_token' => $token,
        'oauth_nonce' => (string)mt_rand(), // a stronger nonce is recommended
        'oauth_timestamp' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0'
    );

    $oauth = array_map("rawurlencode", $oauth); // must be encoded before sorting
    $query = array_map("rawurlencode", $query);

    $arr = array_merge($oauth, $query); // combine the values THEN sort

    asort($arr); // secondary sort (value)
    ksort($arr); // primary sort (key)

    // http_build_query automatically encodes, but our parameters
    // are already encoded, and must be by this point, so we undo
    // the encoding step
    $querystring = urldecode(http_build_query($arr, '', '&'));

    $url = "https://$host$path";

    // mash everything together for the text to hash
    $base_string = $method."&".rawurlencode($url)."&".rawurlencode($querystring);

    // same with the key
    $key = rawurlencode($consumer_secret)."&".rawurlencode($token_secret);

    // generate the hash
    $signature = rawurlencode(base64_encode(hash_hmac('sha1', $base_string, $key, true)));

    // this time we're using a normal GET query, and we're only encoding the query params
    // (without the oauth params)
    $url .= "?".http_build_query($query);
    $url=str_replace("&amp;","&",$url); //Patch by @Frewuill

    $oauth['oauth_signature'] = $signature; // don't want to abandon all that work!
    ksort($oauth); // probably not necessary, but twitter's demo does it

    // also not necessary, but twitter's demo does this too
    function add_quotes($str) { return '"'.$str.'"'; }
    $oauth = array_map("add_quotes", $oauth);

    // this is the full value of the Authorization line
    $auth = "OAuth " . urldecode(http_build_query($oauth, '', ', '));

    // if you're doing post, you need to skip the GET building above
    // and instead supply query parameters to CURLOPT_POSTFIELDS
    $options = array( CURLOPT_HTTPHEADER => array("Authorization: $auth"),
                      //CURLOPT_POSTFIELDS => $postfields,
                      CURLOPT_HEADER => false,
                      CURLOPT_URL => $url,
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_SSL_VERIFYPEER => false);

    // do our business
    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);

    $twitter_data = json_decode($json);

    $tweet_data = array();
    
    foreach ($twitter_data as &$value) {
         
       $tweetout = preg_replace("/(http:\/\/|(www\.))(([^\s<]{4,68})[^\s<]*)/", '<a href="http://$2$3" target="_blank">$1$2$4</a>', $value->text);
       $tweetout = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweetout);
       $tweetout = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweetout);
 
       $tweet_data[] = array('text' => $tweetout, 'name' => $value->user->name, 'screen_name' => $value->user->screen_name, 'time' => date('d.m.Y',  strtotime($value->created_at)));
    }

    return $tweet_data;
 
}

if(!session_id()) {        
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/wp-includes/pluggable.php');

if(empty($_POST) && !empty($_GET['action'])) {
    $_POST['action'] = $_GET['action'];
}

if(!empty($_POST)) {
    metaspace_texts_store_post();        
}

add_action('admin_menu', 'metaspace_admin_settings');
add_action('admin_menu', 'metaspace_texts_admin');

function metaspace_admin_settings() {

    add_options_page('metaspace', 'Settings metaspace', 0, 'metaspace_option', 'metaspace_option_page');
}

function metaspace_texts_admin() {
       
    add_menu_page('Texts', 'Text blocks', 8, 'texts', 'metaspace_texts_admin_edit', '', 4);
   
}

function metaspace_texts_store_post() {
    

    global $wpdb;
    
     // Сохранение настроек
    if(isset($_POST['metaspace_cases_form'])) {
        
        if(function_exists('current_user_can') && !current_user_can('manage_options')) {
            
            die( _e('Hacker?'));
           
        }
        
        if(function_exists('check_admin_referer')) {
            check_admin_referer('metaspace_base_setup_form');
        }
      

        $metaspace_admin_email = $_POST['metaspace_admin_email'];  
        $twitter_key = $_POST['twitter_key'];  
        $twitter_key_secret = $_POST['twitter_key_secret'];  
        $twitter_token = $_POST['twitter_token'];  
        $twitter_token_secret = $_POST['twitter_token_secret'];  
        $twitter_screen_name = $_POST['twitter_screen_name'];  
        $twitter_link = $_POST['twitter_link'];  
        $twitter_count_tweets_on_page = $_POST['twitter_count_tweets_on_page'];  
 
        update_option('metaspace_admin_email', $metaspace_admin_email);
        update_option('metaspace_twitter_key', $twitter_key);
        update_option('metaspace_twitter_key_secret', $twitter_key_secret);
        update_option('metaspace_twitter_token', $twitter_token);
        update_option('metaspace_twitter_token_secret', $twitter_token_secret);
        update_option('metaspace_twitter_screen_name', $twitter_screen_name);
        update_option('metaspace_twitter_link', $twitter_link);
        update_option('metaspace_twitter_count', $twitter_count_tweets_on_page);
        
        
        $_SESSION['metaspace_message'] = array('error' => 0, 'message' => 'Settings saved');

        wp_redirect('/wp-admin/options-general.php?page=metaspace_option');
        exit();
    }
        
    $table_texts = $wpdb -> prefix.texts;
    
    // Добавление / редактирование публикации   
    if(isset($_POST['metaspace_texts_edit_texts_form'])) {
        
        $flag_new_texts = false;

        
        if(function_exists('current_user_can') && !current_user_can('manage_options')) {
            
            die( _e('Hacker?'));
           
        }
        
        if(function_exists('check_admin_referer')) {
            check_admin_referer('improve_base_setup_form');
        }
        
               
        $post_text = $_POST['text'];       
   
        $texts_id = (int)$_POST['texts_id'];
       
        
               
        if(!$texts_id) {
            $_SESSION['texts_message'] = array('error' => 1, 'message' => 'Error');  
            
            wp_redirect('/wp-admin/admin.php?page=texts');
            exit();
        }
        else {            
                             
            $wpdb->update( 
                    $table_texts, 
                    array( 
                            'text' => $post_text                           
                    ), 
                    array( 'id' => $texts_id ), 
                    array('%s'),
                    array( '%d' ) 
            );

            
        }
        
       
        wp_redirect('/wp-admin/admin.php?page=texts&action=edit&id='.$texts_id);
        $_SESSION['texts_message'] = array('error' => 1, 'message' => 'Text updated');  
       
        exit();
        
    }          
    
}

/*
 * Страница публикаций
 * 
 */

function metaspace_texts_admin_edit() {
        
    global $wpdb;
    
    if(isset($_SESSION['texts_message'])) {
        echo "<h3>".$_SESSION['texts_message']['message']."</h3>";
        unset($_SESSION['texts_message']);
    }
    
    $action = $_GET['action'];
                
    $table_texts = $wpdb -> prefix.texts;
    
    if($action == 'edit')
    {    
       
        $title = 'Edit';
        $texts_data = metaspace_texts_get_by_id($_GET['id']);
              

        echo '<div class="wrap"><h1>'.$title.' <a class="page-title-action" href="?page=texts">Return</a></h1>';
  
        
        echo "<form method='post' action='". $_SERVER['PHP_SELF']."?page=texts' enctype='multipart/form-data' >
              <input type='hidden' name='metaspace_texts_edit_texts_form' value='1'>
              <input type='hidden' name='texts_id' value='".$texts_data['id']."'>";

        if(function_exists('wp_nonce_field')) {
            wp_nonce_field('improve_base_setup_form');
        }
        
        ob_start();
        wp_editor(@$texts_data['text'], 'wpeditortext', array('textarea_name' => 'text', 'textarea_rows' => '15') );        
        $text = ob_get_contents();        
        ob_end_clean();

      
        echo "<table width='80%'>          
            <tr>
                <td valign='top'>Text:</td>
                <td>".$text."</td>
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
        
        $textss_list = $wpdb -> get_results("SELECT * FROM $table_texts  ORDER BY `id` ASC", ARRAY_A);
         
        echo '<div class="wrap"><h1>Text blocks</h1>';
          
        echo "<table class = 'wp-list-table widefat fixed striped posts'>
              <thead>
              <tr>
                    <td width='300px'>Title</td>                  
                    <td></td>
              </tr>
              </thead>
              <tbody>";  
        
       
        foreach($textss_list as $v) {
             echo "<tr>                 
                    <td class = 'ow-title'>".$v['title']."</td>                           
                    <td class = 'ow-title'><a href='?page=texts&action=edit&id=".$v['id']."'>edit</a></td>
                  </tr>"; 
        }
        
        echo "</tbody>             
              </table>";
    }
}




function metaspace_texts_get_by_id($id) {
    global $wpdb;
    
    if(empty($id))
        return false;
    
    $table_texts = $wpdb -> prefix.texts;
       
    $result = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $table_texts WHERE id = %d", $id), ARRAY_A); 
    
    return $result;
}

function metaspace_texts_get_by_name($name) {
    global $wpdb;
    
    if(empty($name))
        return false;
    
    $table_texts = $wpdb -> prefix.texts;
       
    $result = $wpdb->get_row($wpdb->prepare( "SELECT `text` FROM $table_texts WHERE name = %s", $name), ARRAY_A); 

    return $result['text'];
}


/*
 * Глобальные настройки
 * 
 */
function metaspace_option_page() {
      
    add_option('metaspace_admin_email', 'ef@wearewowagency.com');
    add_option('metaspace_twitter_key', '');
    add_option('metaspace_twitter_key_secret', '');
    add_option('metaspace_twitter_token', '');
    add_option('metaspace_twitter_token_secret', '');
    add_option('metaspace_twitter_screen_name', '');
    add_option('metaspace_twitter_link', '');
    add_option('metaspace_twitter_count', '');
       
    if(isset($_SESSION['metaspace_message'])) {
        echo "<h3>".$_SESSION['metaspace_message']['message']."</h3>";
        unset($_SESSION['metaspace_message']);
    }
    
    echo "<h2>Setting Metaspace</h2>";
        
    echo "<form method='post' action='". $_SERVER['PHP_SELF']."?page=metaspace_option' >
          <input type='hidden' name='metaspace_cases_form' value='1'>";
    
    if(function_exists('wp_nonce_field')) {
        wp_nonce_field('metaspace_base_setup_form');
    }
    
      $token = '352279227-uB9THUQ4P5fXALcgATgZlpeQ2ws7IL1WvX8N0Z5c';
    $token_secret = '94q4N2ToICb2h0v6F1xCIUiRCMTMw5Rmho1YWirnW8F3v';
    $consumer_key = 'EbOmtGCMoo1KxrGIFKFCiEKdi';
    $consumer_secret = 'GlsHISV0AyKQiZQPf9tkrx0ujFmDfXfyLhkctKgKf04Xlq7e6I';
    
    echo "<table width='80%'>       
        <tr>
            <td width='150px;' style='text-aling:right;'>E-mail for contacts:</td>
            <td><input style='width:100%' type='text' name='metaspace_admin_email' value='".  get_option('metaspace_admin_email')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter key:</td>
            <td><input style='width:100%' type='text' name='twitter_key' value='".  get_option('metaspace_twitter_key')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter key secret:</td>
            <td><input style='width:100%' type='text' name='twitter_key_secret' value='".  get_option('metaspace_twitter_key_secret')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter token:</td>
            <td><input style='width:100%' type='text' name='twitter_token' value='".  get_option('metaspace_twitter_token')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter token secret:</td>
            <td><input style='width:100%' type='text' name='twitter_token_secret' value='".  get_option('metaspace_twitter_token_secret')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter screen name:</td>
            <td><input style='width:100%' type='text' name='twitter_screen_name' value='".  get_option('metaspace_twitter_screen_name')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter link for read all:</td>
            <td><input style='width:100%' type='text' name='twitter_link' value='".  get_option('metaspace_twitter_link')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
            <td width='150px;' style='text-aling:right;'>Twitter count tweets on page:</td>
            <td><input style='width:100%' type='text' name='twitter_count_tweets_on_page' value='".  get_option('metaspace_twitter_count')."'></td>
            <td style='color:#666666'><i></i></td>
        </tr>
        <tr>
        <td colspan=3 align='right'><input style='width:140px; height:25px' type='submit' value='Save'></td>
        </tr>
        </table>";
}

 
?>
