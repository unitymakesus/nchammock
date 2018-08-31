<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'WPINC' ) ) { die; }


/**
 * Class PolicyLoginPasswordReset
 * @package SecuritySafe
 */
class PolicyLoginPasswordReset {


    /**
     * PolicyLoginPasswordReset constructor.
     */
	function __construct(){

        // Disable Password Reset Form
        add_filter( 'allow_password_reset', '__return_false' );

        // Replace Link Text With Null
        add_filter( 'gettext', array( $this, 'remove' ) );

	} // __construct()


    /**
     * Replaces reset password text with nothing
     */ 
    public function remove( $text ){
        
        $replace = array( 
            'Lost your password?', 
            'Lost your password',
            );

        $text = str_replace( $replace, '', trim( $text, '?' ) ); 

        // Memory Cleanup
        unset( $replace );
        
        return $text;

    } // remove()


} // PolicyLoginPasswordReset()
