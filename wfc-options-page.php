<?php

register_deactivation_hook( __FILE__, 'wfc_ext_delete_plugin_options' );
register_uninstall_hook( __FILE__, 'wfc_ext_delete_plugin_options' );
add_action( 'admin_menu', 'wfc_ext_add_options_page' );
add_action( 'admin_init', 'wfc_ext_register_settings' );
add_filter( 'plugin_action_links', 'wfc_ext_plugin_action_links', 10, 2 );

// Display a Settings link on the main Plugins page
function wfc_ext_plugin_action_links( $links, $file ) {
  if ( $file == plugin_basename( __FILE__ ) ) {
    $wfc_ext_links = '<a href="'.get_admin_url().'options-general.php?page=wfc-plugin">'.__( 'Settings' ).'</a>';
    array_unshift( $links, $wfc_ext_links );
  }
  return $links;
}

function wfc_ext_delete_plugin_options() {
  delete_option( 'wfc_ext_options' );
}

function wfc_ext_register_settings() {
  register_setting( 'wfc_ext_options', 'wfc_ext_enable_ifbyphone' );
  register_setting( 'wfc_ext_options', 'wfc_ext_ibp_public_key' );
  register_setting( 'wfc_ext_options', 'wfc_ext_ibp_formatting' );
  register_setting( 'wfc_ext_options', 'wfc_ext_ibp_keyword_set' );
  register_setting( 'wfc_ext_options', 'wfc_ext_real_number' );
  register_setting( 'wfc_ext_options', 'wfc_ext_method' );
  register_setting( 'wfc_ext_options', 'wfc_ext_dynamic_number_replace' );
}

function wfc_ext_add_options_page() {
  add_options_page( 'WFC Extended Options', 'WFC Extended Options', 'manage_options', 'wfc-plugin', 'wfc_ext_render_form' );
}

function wfc_ext_render_form() {
?>
   <div class="wrap">
            <div id="icon-options-general" class="icon32">
                <br>
            </div>
            <h2>WFC Extended Items</h2>
            <form method="post" action="options.php">
                 <?php settings_fields( 'wfc_ext_options' ); ?>
                <table class="form-table">
                    <tbody>
                      <tr valign="top">
                          <th scope="row">Check to enable IfByPhone Script:</th>
                          <td><input name="wfc_ext_enable_ifbyphone" type="checkbox" value="On"
                              <?php  if ( get_option( 'wfc_ext_enable_ifbyphone' ) == "On" ) echo 'checked="checked"'; ?> />
                          </td>
                      </tr>
                      <tr valign="top">
                          <th scope="row">Select which Method to use:</th>
                          <td>
                            <label>Basic Script</label>
                            <input name="wfc_ext_method" type="radio" value="Basic Script" 
                              <?php  if ( get_option( 'wfc_ext_method' ) == "Basic Script" ) echo 'checked="checked"'; ?> />
                              <label for="">SourceTrak API</label>
                            <input name="wfc_ext_method" type="radio" value="SourceTrak API"
                              <?php  if ( get_option( 'wfc_ext_method' ) == "SourceTrak API" ) echo 'checked="checked"'; ?> />
                          </td>
                      </tr>
                      <tr valign="top">
                            <th scope="row">Enter ibp_public_key:</th>
                            <td><input name="wfc_ext_ibp_public_key" type="text" 
                              value="<?php echo get_option( 'wfc_ext_ibp_public_key' ); ?>" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Enter ibp_formatting:</th>
                            <td><input name="wfc_ext_ibp_formatting" type="text" 
                              value="<?php echo get_option( 'wfc_ext_ibp_formatting' ); ?>" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Enter ibp_keyword_set:</th>
                            <td><input name="wfc_ext_ibp_keyword_set" type="text" 
                              value="<?php echo get_option( 'wfc_ext_ibp_keyword_set' ); ?>" />
                            </td>
                        </tr>   
                        <tr valign="top">
                            <th scope="row">Enter API Key:</th>
                            <td><input name="wfc_ext_ibp_api_key" type="text" 
                              value="<?php echo get_option( 'wfc_ext_ibp_api_key' ); ?>" />
                            </td>
                        </tr>                        
                        <tr valign="top">
                            <th scope="row">Real Phone number to be used when IfByPhone is deactivated:</th>
                            <td><input name="wfc_ext_real_number" type="text" 
                              value="<?php echo get_option( 'wfc_ext_real_number' ); ?>" />
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"></th>
                            <td><input type="hidden" name="wfc_extended_action" value="save_settings" /></td>
                        </tr>
                        <tr valign="top">
                          <th scope="row">Automically replace numbers with shortcode.  ** THIS IS IN BETA **:</th>
                          <td>
                            <label>Off</label>
                            <input name="wfc_ext_dynamic_number_replace" type="radio" value="0" <?php checked( '0', get_option( 'wfc_ext_dynamic_number_replace' ) ); ?> />
                            <label for="">On</label>
                            <input name="wfc_ext_dynamic_number_replace" type="radio" value="1" <?php checked( '1', get_option( 'wfc_ext_dynamic_number_replace' ) ); ?> />                            
                          </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" value="Save Changes">
                </p>
            </form>
        </div>
   <?php
}


//add_action( 'admin_print_scripts', 'wfc_ext_disable_admin_bar' );
function wfc_ext_disable_admin_bar() {
  echo '<style type="text/css">#wpadminbar { display: none; }</style>';
}


//specific functions
if ( get_option( 'wfc_ext_enable_ifbyphone' ) && get_option( 'wfc_ext_method' ) == "Basic Script"){
  add_action( 'wp_head', 'wfc_ifbyphone_init' );
}

function wfc_ifbyphone_init() {
?>
<!-- WFC IfByPhone Start -->
<script type="text/JavaScript" src="https://secure.ifbyphone.com/js/ibp_clickto_referral.js"></script>
<script type="text/JavaScript">
    var _ibp_public_key = "<?php echo get_option( 'wfc_ext_ibp_public_key' ); ?>";
    var _ibp_formatting = <?php echo get_option( 'wfc_ext_ibp_formatting' ); ?>;
    var _ibp_keyword_set = <?php echo get_option( 'wfc_ext_ibp_keyword_set' ); ?>;
</script>
<!-- WFC IfByPhone End -->
    <?php
}

if ( get_option( 'wfc_ext_dynamic_number_replace' ) == "1"){
  add_filter('the_content','wfc_swap_phone_for_code');
}

function wfc_swap_phone_for_code($c){
  // $rn = get_option( 'wfc_ext_real_number' );
  // $prep = str_replace("-", "\-", $rn);
  $pattern = '/704\-323\-8004/i';
  $new_c = preg_replace($pattern, "[wfc_phone]", $c);
  return $new_c;
}

class Wfc_Bonus_Class {

  public function Init() {
    $this->wfc_shortcode_widget();
    add_shortcode( 'wfc_phone', array( 'Wfc_Bonus_Class', 'shortCode' ) );

  }

  public function wfc_shortcode_widget(){
    add_filter( 'widget_text', 'do_shortcode' );
  }

  public function shortCode( $atts ) {
//[wfc_phone city="Rockingham"]
    extract( shortcode_atts( array(
        'city' => 'Toll Free'
     ), $atts ) );

    if(get_option( 'wfc_ext_method' ) == "SourceTrak API"){

      global $ibpinit;

      $ibpinit->setID = $ibpinit->city_set_id[$city];

      if (isset($_GET['_ibp_unique_id'])) {
          $_ibp_unique_id = $_GET['_ibp_unique_id'];
      } else {
          $_ibp_unique_id = '';
      }


      if (isset($_GET['log_id'])) {
          $log_id = $_GET['log_id'];
      } else {
          $log_id = "";
      }


      // workaround since cookies are not accessible until the next page load after they are set
      $wfc_ibp_referer = isset($_COOKIE['wfc_ibp_referer']) ? $_COOKIE['wfc_ibp_referer'] : wp_get_referer();
      $wfc_ibp_base_url = isset($_COOKIE['wfc_ibp_base_url']) ? $_COOKIE['wfc_ibp_base_url'] : $ibpinit->getUrl();
      
      $sourcetrakNumber =  $ibpinit->SourceTrak(
            $ibpinit->setID, 
            $_ibp_unique_id, 
            $log_id, 
            $ibpinit->api_key, 
            $wfc_ibp_referer, 
            $wfc_ibp_base_url
        );
      /*

      Array ( [type] => SimpleXMLElement Object ( [0] => main ) [log_id] => SimpleXMLElement Object ( [0] => 2406720924 ) [number] => SimpleXMLElement Object ( [0] => (910) 895-3075 ) )
      */
    }


    if ( get_option( 'wfc_ext_enable_ifbyphone' ) == "On" ) {
      if(get_option( 'wfc_ext_method' ) == "Basic Script"){
        return '<script type="text/JavaScript" src="https://secure.ifbyphone.com/js/keyword_replacement.js"></script>';
      }else{
        return $sourcetrakNumber['number'];
      }
    }else {

      switch ($city) {
        case 'Rockingham':
            return "(910) 895-3075";
          break;
        case 'Monroe':
            return "(704) 283-1527";
          break;
        case 'Charlotte':
            return "(704) 536-1133";
          break;        
        default:
            return "1-800-71-NOPEST";
          break;
      }
      return get_option( 'wfc_ext_real_number' );
    }
  }
}


