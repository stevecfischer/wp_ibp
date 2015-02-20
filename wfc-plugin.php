<?php
/*
Plugin Name: WFC Ifbyphone
Description: General extended functions
Version: 2.3
Author: Steve Fischer
License: GPLv2 or later
*/

$prefix = "wfc-";

if ( !defined( 'WFC_EXT_PATH' ) ) {
  define( 'WFC_EXT_PATH', dirname( __FILE__ ).'/' );
}

if ( !defined( 'IBP' ) ) {
  define( 'IBP', dirname( __FILE__ ).'/ifbyphoneapi/' );
}

if ( !defined( 'WFC_EXT_URL' ) ) {
  $aa = __FILE__;
  $plugin_dir_url = plugin_dir_url( basename( $aa ) ) . 'wfc-ifbyphone/';
  define( 'WFC_EXT_URL', $plugin_dir_url );
}

add_action( 'wp_enqueue_scripts', 'wfc_ext_scripts' );
function wfc_ext_scripts() {
  wp_enqueue_script( 'jquery' );
  wp_register_script( "jquery.wfc.fn", WFC_EXT_URL.'js/jquery.wfc.fn.js', array( 'jquery' ), '', true );
  wp_enqueue_script( "jquery.wfc.fn" );
}




// Set-up Action and Filter Hooks
//register_activation_hook(__FILE__, 'wfc_ext_add_defaults');

require_once(IBP.'sourceTrakAPIclass.php');

class wfc_ibp extends sourceTrakAPI
{  
  protected $ibp;
  private $data = array();

  //function SourceTrak($setID, $_ibp_unique_id, $log_id, $api_key, $referer = null, $baseURI = null)


    public function __set($name, $value)
    {        
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function setCookie(){
      $cookie_timeout = 3600;
      if(!isset($_COOKIE['wfc_ibp_referer'])){        
        setcookie('wfc_ibp_referer', wp_get_referer(), (time()+$cookie_timeout), "/");
        setcookie('wfc_ibp_base_url', $this->getUrl(), (time()+$cookie_timeout), "/");
      }
    }

    public function getUrl() {
      $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }
}
$ibpinit = new wfc_ibp;




  $config = array(
    $prefix.'dashboard-widget' => false,
    $prefix.'options-page' => true
  );

foreach($config as $k => $v){
    if($v) require_once $k.'.php';
}

$ibpinit->city_set_id = array(
  "Rockingham"=>"75264",
  "Monroe"=>"75254",
  "Charlotte"=>"75244",
  "Toll Free"=>"75234"
  );
$ibpinit->api_key="d7d246cd4758571693849a76bceddbaf129ad4a1";


add_action('init',array($ibpinit,'setCookie'));

$init_bonus_class = new Wfc_Bonus_Class;
add_action('init',array($init_bonus_class,'Init'));

