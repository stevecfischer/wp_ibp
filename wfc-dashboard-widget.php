<?php

class Wfc_Dashboard_Widget {

    const wid = 'wfc_review_plugin';

    public function __construct(){
        add_action( 'wp_dashboard_setup', array( $this, 'wfc_ext_init' ) );
    }

    public static function wfc_ext_init() {
        self::wfc_ext_update_dashboard_widget_options(
            self::wid,
            array(
                'wfc_review' => "Write review here.",
            ),
            true
        );

        wp_add_dashboard_widget(
            self::wid,
            __( 'WFC Dashboard Widget', 'nouveau' ),
            array( 'Wfc_Dashboard_Widget', 'wfc_ext_widget' ),
            array( 'Wfc_Dashboard_Widget', 'wfc_ext_config' )
        );
    }

    public static function wfc_ext_widget() {
        echo "<p>Review:</p>
<p><?php echo self::wfc_ext_get_dashboard_widget_option(self::wid, 'wfc_review'); ?></p>";
    }

    public static function wfc_ext_config() {
        $review = stripslashes( $_POST['wfc-review'] );
        self::wfc_ext_update_dashboard_widget_options(
            self::wid,                                  //The  widget id
            array(                                      //Associative array of options & default values
                'wfc_review' => $review,
            )
        );
?>
        <p>Enter the review to display in the header.</p>
        <textarea style="width: 90%;float: left;height: 100px;margin-bottom: 10px;" name="wfc-review"></textarea>
<?php
    }

    public static function wfc_ext_get_dashboard_widget_options( $widget_id='' ) {
        $opts = get_option( 'dashboard_widget_options' );
        if ( empty( $widget_id ) )
            return $opts;

        if ( isset( $opts[$widget_id] ) )
            return $opts[$widget_id];

        return false;
    }

    public static function wfc_ext_get_dashboard_widget_option( $widget_id, $option, $default=NULL ) {
        $opts = self::wfc_ext_get_dashboard_widget_options( $widget_id );
        if ( ! $opts )
            return false;

        if ( isset( $opts[$option] ) && ! empty( $opts[$option] ) )
            return $opts[$option];
        else
            return ( isset( $default ) ) ? $default : false;

    }

    public static function wfc_ext_update_dashboard_widget_options( $widget_id , $args=array(), $add_only=false ) {
        $opts = get_option( 'dashboard_widget_options' );
        $w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();

        if ( $add_only ) {
            $opts[$widget_id] = array_merge( $args, $w_opts );
        }
        else {
            $opts[$widget_id] = array_merge( $w_opts, $args );
        }

        return update_option( 'dashboard_widget_options', $opts );
    }
}
new Wfc_Dashboard_Widget;