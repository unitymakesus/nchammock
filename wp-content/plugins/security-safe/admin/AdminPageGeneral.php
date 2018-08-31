<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Class AdminPageGeneral
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageGeneral extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        $this->slug = 'security-safe';
        $this->title = 'Welcome to Security Safe';
        $this->description = 'Thank you for choosing Security Safe to help protect your website.';

        $this->tabs[] = array(
            'id' => 'settings',
            'label' => 'Settings',
            'title' => 'Plugin Settings',
            'heading' => 'These are the general plugin settings.',
            'intro' => '',
            'content_callback' => 'tab_general',
        );

        $this->tabs[] = array(
            'id' => 'info',
            'label' => 'Information',
            'title' => 'Plugin Information',
            'heading' => 'This information may be useful when troubleshooting compatibility issues or bugs.',
            'intro' => '',
            'content_callback' => 'tab_info',
        );

    } // set_page()

    /**
     * All General Tab Content
     * @since  0.3.0
     * @return $html html
     */ 
    public function tab_general() {

        $html = '';

        // General Settings ================
        $html .= $this->form_section( 'General Settings', false );
        
            // Shutoff Switch - All Security Policies
            $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';
            $rows = $this->form_select( $this->settings, 'All Security Policies', 'on', array( '0' => 'Disabled', '1' => 'Enabled' ), 'If you experience a problem, you may want to temporarily turn off all security policies at once to troubleshoot the issue. You can temporarily disable each type of policy at the top of each settings tab.', $classes );
            
            // Reset Settings
            $classes = '';
            $rows .= $this->form_button( 'Reset Settings', 'link-delete', get_admin_url() . '?page=security-safe&reset=1', 'Click this button to reset the Security Safe settings back to default. WARNING: You will lose all configuration changes you have made.', $classes );
            
            // Cleanup Database
            $classes = '';
            $rows .= $this->form_checkbox( $this->settings, 'Cleanup Database When Disabling Plugin', 'cleanup', 'Remove Settings When Disabled', 'If you ever decide to disable this plugin, you may want us to remove our settings from the database. Do not check this box if you are temporarily disabling the plugin.', $classes, false );      
        
        $html .= $this->form_table( $rows );

        // Save Button
        $html .= $this->button( 'Save Settings' );

        // Memory Cleanup
        unset( $rows, $classes );

        return $html;

    } // tab_general()


    /**
     * All General Tab Content
     * @since  1.1.0
     * @return $html html
     */ 
    public function tab_info() {

        $html = '';

        $html .= '<h3>Current Settings</h3>';
        $settings = get_option('securitysafe_options');

        // Get Plugin Settings
        ob_start();
        
        echo '<pre style="width: 100%;">';
        var_dump( $settings );
        echo '</pre>';

        $html .= ob_get_clean();

        $html .= '<p></p>';
        $html .= '<h3>Installed Plugin Version History</h3>';

        $history = $settings['plugin']['version_history'];

        $html .= '<ul>';

        foreach ( $history as $past ) {
            $html .= '<li>' . $past . '</li>';
        }

        $html .= '</ul>';

        return $html;

    } // tab_info()


} // AdminPageGeneral()
