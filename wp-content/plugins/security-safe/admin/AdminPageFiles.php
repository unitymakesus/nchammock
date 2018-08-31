<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * Class AdminPageFiles
 * @package SecuritySafe
 * @since  0.2.0
 */
class AdminPageFiles extends AdminPage {


    /**
     * This sets the variables for the page.
     * @since  0.1.0
     */  
    protected function set_page() {

        global $SecuritySafe;

        $plugin = $SecuritySafe->plugin;

        // Fix Permissions
        $this->fix_permissions();

        $this->slug = 'security-safe-files';
        $this->title = 'Files & Folders';
        $this->description = 'It is essential to keep all files updated and ensure only authorized users can access them.';

        $this->tabs[] = array(
            'id' => 'settings',
            'label' => 'Settings',
            'title' => 'File Settings',
            'heading' => false,
            'intro' => false,
            'content_callback' => 'tab_settings',
        );

        $this->tabs[] = array(
            'id' => 'core',
            'label' => 'Core',
            'title' => 'WordPress Base Directory & Files',
            'heading' => 'Check to make sure all file permissions set correctly.',
            'intro' => 'Incorrect directory or file permission values can lead to security vulnerabilities or even plugins or themes not functioning as intended. If you are not sure what values to set for a file or directory, use the standard recommended value.',
            'classes' => array( 'full' ),
            'content_callback' => 'tab_core',
        );

        $this->tabs[] = array(
            'id' => 'theme',
            'label' => 'Theme',
            'title' => 'Theme Audit',
            'heading' => 'Check to make sure all theme file permissions set correctly.',
            'intro' => 'If you use "Secure" permission settings, and experience problems, just set the file permissions back to "Standard."',
            'classes' => array( 'full' ),
            'content_callback' => 'tab_theme',
        );

        $this->tabs[] = array(
            'id' => 'uploads',
            'label' => 'Uploads',
            'title' => 'Uploads Directory Audit',
            'heading' => 'Check to make sure all uploaded files have proper permissions.',
            'intro' => '',
            'classes' => array( 'full' ),
            'content_callback' => 'tab_uploads',
        );

        $tab_plugins_intro = 'WordPress sets file permissions to minimum safe values by default when you install or update plugins. You will likely find file permission issues after migrating a site from one server to another. The file permissions for a plugin will get fixed when you perform an update on that particular plugin. We would recommend correcting any issues labeled "bad" immediately, versus waiting for an update.';
        
        if ( ! $this->is_pro() ) {
        
            $tab_plugins_intro .= '<br /><br /><b>Batch Plugin Permissions</b> (<a href="' . $plugin['url_more_info_pro'] . '" target="_blank">Pro Feature</a>) - You can change all plugin permissions to Standard or Secure permissions with one click.';
            $tab_plugins_intro .= '<br /><br /><b>Prevent Plugin Version Snooping</b> (<a href="' . $plugin['url_more_info_pro'] . '" target="_blank">Pro Feature</a>) - Prevent access to plugin version files.';
            $tab_plugins_intro .= '<br /><br /><b>Maintain Secure Permissions</b> (<a href="' . $plugin['url_more_info_pro'] . '" target="_blank">Pro Feature</a>) - WordPress will overwrite your file permissions changes when an update is performed. Security Safe Pro will automatically fix your permissions after an update.';
        
        }

        $this->tabs[] = array(
            'id' => 'plugins',
            'label' => 'Plugins',
            'title' => 'Plugins Audit',
            'heading' => 'When plugin updates run, they will overwrite your permission changes.',
            'intro' => $tab_plugins_intro,
            'classes' => array( 'full' ),
            'content_callback' => 'tab_plugins',
        );

        $this->tabs[] = array(
            'id' => 'server',
            'label' => 'Server',
            'title' => 'Server Information',
            'heading' => 'It is your hosting provider\'s job to keep your server up-to-date.',
            'intro' => 'This table below will help identify the software versions currently on your hosting server. <br>NOTE: System administrators often do server updates once per month. If something is a version behind, then you might be between update cycles or there may be compatibility issues due to version dependencies.',
            'classes' => array( 'full' ),
            'content_callback' => 'tab_server',
        );

    } // set_page()


    /**
     * This tab displays file settings.
     * @since  0.2.0
     */ 
    function tab_settings() {

        global $wp_version, $SecuritySafe;

        $plugin = $SecuritySafe->plugin;

        $html = '';

            // Shutoff Switch - All File Policies
            $classes = ( $this->settings['on'] ) ? '' : 'notice-warning';
            $rows = $this->form_select( $this->settings, 'File Policies', 'on', array( '0' => 'Disabled', '1' => 'Enabled' ), 'If you experience a problem, you may want to temporarily turn off all file policies at once to troubleshoot the issue.', $classes );
            $html .= $this->form_table( $rows );

        // Automatic WordPress Updates ================
        $rows = '';
        $html .= $this->form_section( 'Automatic WordPress Updates', 'Updates are one of the main culprits to a compromised website.' );

            if ( version_compare( $wp_version, '3.7.0') >= 0 && ! defined('AUTOMATIC_UPDATER_DISABLED') ) {

                $disabled = ( defined('WP_AUTO_UPDATE_CORE') ) ? true : false;
                $classes = '';

                $rows .= ( $disabled ) ? $this->form_text( '<b>NOTICE:</b> WordPress Automatic Core Updates are being controlled by the constant variable WP_AUTO_UPDATE_CORE in the wp-config.php file or by another plugin. As a result, Automatic Core Update feature settings for this plugin have been disabled.', 'notice-info' ) : '';
                             
                $rows .= $this->form_checkbox( $this->settings, 'Dev Core Updates', 'allow_dev_auto_core_updates', 'Automatic Nightly Core Updates', 'Select this option if the site is in development only.', $classes, $disabled );
                $rows .= $this->form_checkbox( $this->settings, 'Major Core Updates', 'allow_major_auto_core_updates', 'Automatic Major Core Updates', 'If you feel very confident in your code, you could automate the major version upgrades. (not recommended in most cases)', $classes, $disabled );
                $rows .= $this->form_checkbox( $this->settings, 'Minor Core Updates', 'allow_minor_auto_core_updates', 'Automatic Minor Core Updates', 'This is enabled by default in WordPress and only includes minor version and security updates.', $classes, $disabled );
        
                $rows .= $this->form_checkbox( $this->settings, 'Plugin Updates', 'auto_update_plugin', 'Automatic Plugin Updates', $classes, false );
                $rows .= $this->form_checkbox( $this->settings, 'Theme Updates', 'auto_update_theme', 'Automatic Theme Updates', $classes, false );

            } else {

                if ( defined('AUTOMATIC_UPDATER_DISABLED') ) {

                    $rows .= $this->form_text( '<b>NOTICE:</b> WordPress Automatic Updates are disabled by the constant variable AUTOMATIC_UPDATER_DISABLED in the wp-config.php file or by another plugin. As a result, Automatic Update features for this plugin have been disabled.', 'notice-info' );

                } // AUTOMATIC_UPDATER_DISABLED

                if ( version_compare( $wp_version, '3.7.0') < 0 ) {

                    $rows .= $this->form_text( '<b>NOTICE:</b> You are using WordPress Version ' . $wp_version . '. The WordPress Automatic Updates feature controls require version 3.7 or greater.', 'notice-info' );

                } // version_compare()

            } // version_compare()

        $html .= $this->form_table( $rows );

        // File Access
        $html .= $this->form_section( 'File Access', false );
        $classes = '';
        $rows = $this->form_checkbox( $this->settings, 'Theme File Editing', 'DISALLOW_FILE_EDIT', 'Disable Theme Editing', 'Disable the ability for admin users to edit your theme files from the WordPress admin.', $classes, false );
        $rows .= $this->form_checkbox( $this->settings, 'WordPress Version Files', 'version_files_core', 'Prevent Access', 'Prevent access to files that disclose WordPress versions: readme.html and license.txt. Also, see <a href="admin.php?page=security-safe-privacy#software-privacy">Software Privacy</a>.', $classes, false );
        
        if ( $this->is_pro() ) {

            $rows .= $this->form_checkbox( $this->settings, 'Plugin Version Files', 'version_files_plugins', 'Prevent Access', 'Prevent access to files that disclose plugin versions: readme.txt, readme.md, changelog.txt, changelog.md, and license.txt. Also see <a href="admin.php?page=security-safe-privacy#software-privacy">Software Privacy</a>.', $classes, false );
            $rows .= $this->form_checkbox( $this->settings, 'Theme Version Files', 'version_files_themes', 'Prevent Access', 'Prevent access to files that disclose plugin versions: readme.txt, readme.md, changelog.txt, changelog.md, and license.txt. Also see <a href="admin.php?page=security-safe-privacy#software-privacy">Software Privacy</a>.', $classes, false );

        } else {

            $rows .= $this->form_checkbox( $this->settings, 'Plugin Version Files', 'version_files_plugins', 'Prevent Access (<a href="' . $plugin['url_more_info_pro'] . '" target="_blank">Pro Feature</a>)', 'Prevent access to files that disclose plugin versions.', $classes, true );
            $rows .= $this->form_checkbox( $this->settings, 'Theme Version Files', 'version_files_themes', 'Prevent Access (<a href="' . $plugin['url_more_info_pro'] . '" target="_blank">Pro Feature</a>)', 'Prevent access to files that disclose plugin versions.', $classes, true );

        }

        $html .= $this->form_table( $rows );

        // Save Button
        $html .= $this->button( 'Save Settings' );

        // Memory Cleanup
        unset ( $rows );

        return $html;

    } // tab_settings()


    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */ 
    function tab_core() {

        // Determine File Structure
        $plugins_dir = ( defined( 'WP_PLUGIN_DIR' ) ) ? WP_PLUGIN_DIR : dirname ( dirname( __DIR__ ) );
        $content_dir = ( defined( 'WP_CONTENT_DIR' ) ) ? WP_CONTENT_DIR : ABSPATH . 'wp-content';
        $muplugins_dir = ( defined( 'WPMU_PLUGIN_DIR' ) ) ? WPMU_PLUGIN_DIR : $content_dir . '/mu-plugins';
        $uploads_dir = wp_upload_dir();
        $uploads_dir = $uploads_dir["basedir"];
        $themes_dir = dirname( get_template_directory() );

        // Array of Files To Be Checked
        $paths = array(
            $uploads_dir,
            $plugins_dir,
            $muplugins_dir,
            $themes_dir,
        );

        // Remove Trailing Slash
        $base = str_replace( '//', '', ABSPATH . '/' );

        // Get All Files / Folders In Base Directory
        $base = $this->get_dir_files( $base, false );
        
        // Combine File List
        $paths = array_merge( $base, $paths );

        // Get Rid of Duplicates
        $paths = array_unique( $paths );

        // Memory Cleanup
        unset( $plugins_dir, $content_dir, $muplugins_dir, $uploads_dir, $themes_dir, $base );

        return $this->display_permissions_table( $paths );

    } // tab_core()


    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */ 
    function tab_theme() {

        $theme_parent = get_template_directory();
        $theme_child = get_stylesheet_directory();
        
        $files = $this->get_dir_files( $theme_parent );

        if ( $theme_parent != $theme_child ) {
            
            // Child Theme Present
            $child_files = $this->get_dir_files( $theme_child );
            $files = array_merge( $child_files, $files );

        }

        return $this->display_permissions_table( $files, 'tab_theme' );

    } // tab_theme()

    /**
     * This tab displays current and suggested file permissions.
     * @since  1.1.0
     */ 
    function tab_uploads() {

        $uploads_dir = wp_upload_dir();
        $uploads_dir = $uploads_dir["basedir"];

        return $this->display_permissions_table( $this->get_dir_files( $uploads_dir ) );

    } // tab_uploads()


    /**
     * This tab displays current and suggested file permissions.
     * @since  1.0.3
     */ 
    function tab_plugins() {

        $plugins_dir = ( defined( 'WP_PLUGIN_DIR' ) ) ? WP_PLUGIN_DIR : dirname ( dirname( __DIR__ ) );

        return $this->display_permissions_table( $this->get_dir_files( $plugins_dir ), 'tab_plugins' );

    } // tab_plugins()


    /**
     * This tab displays software installed on the server.
     * @since  1.0.3
     */ 
    function tab_server() {

        // Latest Versions
        $latest_versions = array();

        //https://secure.php.net/ChangeLog-7.php
        //https://secure.php.net/ChangeLog-5.php
        $latest_versions['PHP'] = array( 
            '7.2.0' => '7.2.9',
            '7.1.0' => '7.1.21',
            '7.0.0' => '7.0.31',
            '5.6.0' => '5.6.37' );

        $php_min = '5.6.0';

        $ok = array();
        $ok['php'] = false;

        $bad = array();
        $bad['php'] = false;

        $PHP_VERSION = PHP_VERSION;
        //$PHP_VERSION = '5.6.12'; // test only

        $notice_class = '';

        $html = '';

        $html .= '
            <table class="wp-list-table widefat fixed striped file-perm-table" cellpadding="10px">
                <thead>
                    <tr>
                        <th class="manage-column">' . __( 'Description', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 250px;">' . __( 'Current Version', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 250px;">' . __( 'Recommend', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 75px;">' . __( 'Status', 'security-safe' ) . '</th>
                    </tr>
                </thead>';

        $versions = array();

        // PHP Version
        if( defined('PHP_VERSION') ) {

            $status = '';
            $recommend = '';

            if ( in_array( $PHP_VERSION, $latest_versions['PHP'] ) ) {

                // PHP Version Is Secure
                $status = 'Secure';
                $recommend = $PHP_VERSION;

            } else if ( version_compare( $PHP_VERSION, $php_min, '<' ) ) {
                
                // This Version Is Vulnerable
                $status = 'Bad';
                $recommend = $latest_versions['PHP'][ $php_min ];

                $bad['php'] = array( $PHP_VERSION, $php_min );
                $notice_class = 'notice-error';
           
            } else {

                // Needs Update To Latest Secure Patch Version
                foreach ( $latest_versions['PHP'] as $minor => $patch ) {

                    if ( version_compare( $PHP_VERSION, $minor, '>=' ) ) {
                        
                        $status = 'OK';
                        $recommend = $patch;

                        $ok['php'] = array( $PHP_VERSION, $patch );
                        $notice_class = 'notice-warning';

                        break;

                    }

                } // foreach()

            } // endif

            $versions[] = array( 
                'name' => 'PHP', 
                'current' => $PHP_VERSION,
                'recommend' => $recommend, 
                'status' => $status,
                'class' => $notice_class,
            );

        } // PHP_VERSION

        // Get All Versions From phpinfo
        $phpinfo = $this->get_phpinfo(8);

        if ( ! empty( $phpinfo ) ) {

            foreach ( $phpinfo as $name => $section ) {
                
                foreach ( $section as $key => $val ) {
                    
                    if ( strpos( strtolower( $key ), 'version') !== false && strpos( strtolower( $key ), 'php version') === false ) {
                        
                        if ( is_array($val) ) {
                            
                            $current = $val[0];
                        
                        } elseif ( is_string( $key ) ) {
                            
                            $current = $val;
                        
                        } // is_array()

                        // Remove Duplicate Text
                        $name = $name . ': ' . str_replace( $name, '', $key );

                        $versions[] = array( 
                            'name' => $name, 
                            'current' => $current,
                            'recommend' => '-', 
                            'status' => '-',
                            'class' => '',
                        );

                    } // strpos()

                } // foreach()

            } // foreach()
        
        } // ! empty()

        // Display All Version
        foreach ( $versions as $v ) {

            $html .= '<tr class="' . $v['class'] . '">';
            $html .= '<td class="check-column">' . __( $v['name'] ) . '</td>';
            $html .= '<td class="check-column" style="text-align: center;">' . __( $v['current'], 'security-safe' ) . '</td>';
            $html .= '<td class="check-column" style="text-align: center;">' . __( $v['recommend'], 'security-safe' ) . '</td>';
            $html .= '<td class="check-column ' . strtolower( $v['status'] ) . '" style="text-align: center;">' . __( $v['status'], 'security-safe' ) . '</td>';
            $html .= '</tr>';

        } // foreach

        // If phpinfo is disabled, display notice
        if ( empty( $phpinfo ) ) {

            $html .= '<tr><td colspan="4">It seems that the phpinfo() function is disabled. You may need to contact the hosting provider to enable this function for more advanced version details. <a href="http://php.net/manual/en/function.phpinfo.php">See the documentation.</a></td></tr>';
        
        } // ! empty()

        $html .= '</table>';

        // Display Notices
        $this->display_notices_perms( false, $ok, $bad );

        // Memory Cleanup
        unset( $latest_versions, $versions, $status, $recommend, $phpinfo, $name, $section, $key, $val, $current, $v, $php_min, $minor, $patch, $bad );

        return $html;

    } // tab_server()


    /**
     * Returns phpinfo as an array
     * @since  1.0.3
     */ 
    private function get_phpinfo( $type = 1 ) {

        ob_start();

        phpinfo( $type );

        $phpinfo = array();
        $pattern = '#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s';

        if ( preg_match_all( $pattern, ob_get_clean(), $matches, PREG_SET_ORDER)){
            
            foreach ( $matches as $m ) {
            
                if ( strlen( $m[1] ) ) {
                    
                    $phpinfo[ $m[1] ] = array();
                
                } else {

                    $keys = array_keys( $phpinfo );

                    if ( isset( $m[3] ) ) {
                    
                        $phpinfo[ end( $keys ) ][ $m[2] ] = ( isset( $m[4] ) ) ? array( $m[3], $m[4] ) : $m[3];
                
                    } else {
                    
                        $phpinfo[ end( $keys ) ][] = $m[2];

                    } // isset()

                } // strlen()
        
            } // foreach()
            
        } // preg_match_all()

        // Memory Cleanup
        unset( $type, $pattern, $matches, $m, $keys );

        return $phpinfo;

    } // get_phpinfo()


    /**
     * Display all file permissions in a table
     * @param  array $paths An array of absolute paths
     * @param  string $tab Tab identification: used to determine features for one tab versus another.
     * @since  1.0.3
     */
    private function display_permissions_table( $paths = false, $tab = false ) {

        $html = '
            <table class="wp-list-table widefat fixed striped file-perm-table">
                <thead>
                    <tr>
                        <th class="manage-column">' . __( 'Relative Location', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 100px;"">' . __( 'Type', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 75px;"">' . __( 'Current', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 70px;"">' . __( 'Status', 'security-safe' ) . '</th>
                        <th class="manage-column" style="width: 160px;"">' . __( 'Modify', 'security-safe' ) . '</th>
                    </tr>
                </thead>';

        if ( is_array( $paths ) && ! empty ( $paths ) ) { 

            $file_count = 0;

            $good = array();
            $good['dirs'] = 0;
            $good['files'] = 0;

            $bad = array();
            $bad['dirs'] = 0;
            $bad['files'] = 0;

            $ok = array();
            $ok['dirs'] = 0;
            $ok['files'] = 0;

            foreach ( $paths as $p ) {

                if ( file_exists( $p ) ){

                    // Get Relative Path
                    $rel_path = str_replace( array( ABSPATH, '//' ), '/', $p );

                    // Get File Type
                    $is_dir = is_dir( $p );

                    // Get Details of Path
                    $info = @stat( $p );
                    $permissions = sprintf( '%o', $info['mode'] ); // Get all info about permissions
                    $current = substr( $permissions, -3 ); // Get current o/g/w permissions
                    $perm = str_split( $current ); // Convert permissions to an array
                    
                    // Specific Role Permissions
                    $owner = ( isset( $perm[0] ) ) ? $perm[0] : 0;
                    $group = ( isset( $perm[1] ) ) ? $perm[1] : 0;
                    $world = ( isset( $perm[2] ) ) ? $perm[2] : 0;

                    $notice_class = '';

                    if ( $rel_path == '/' ) {

                        $type = 'directory';
                        $status = 'default';

                    } else {

                        // Determine Directory or File
                        if ( $is_dir ) {

                            $type = 'directory';
                            $min = '775'; // Standard
                            $sec = '755'; // Secure
                            
                            if ( $current == $min || $current == $sec ) {

                                $status = ( $current == $sec ) ? 'secure' : 'good';

                                if ( $status == 'good' && ( $this->is_pro() || ( $tab != 'tab_plugins' && $tab != 'tab_theme' ) ) ) {

                                    $good['dirs'] = $good['dirs'] + 1;
                                    $notice_class = 'notice-info';

                                }

                            } else {

                                // Ceiling
                                $status = ( $world > 5 ) ? 'bad' : 'ok';

                                if ( $status == 'bad' ) {

                                    $bad['dirs'] = $bad['dirs'] + 1;
                                    $notice_class = 'notice-error';

                                } else {

                                    $ok['dirs'] = $ok['dirs'] + 1;
                                    $notice_class = 'notice-warning';

                                }

                            } // $current

                        } else {

                            $type = 'file';
                            $min = '644'; // Standard
                            $sec = '644'; // Secure

                            // Secure Permissions for certain files
                            // https://codex.wordpress.org/Changing_File_Permissions#Finding_Secure_File_Permissions
                            
                            $sec = ( stripos( $p, 'wp-config.php' ) ) ? '600' : $sec;
                            $sec = ( stripos( $p, 'php.ini' ) ) ? '600' : $sec;

                            // Prevent Version Snooping
                            $sec = ( stripos( $p, 'readme.html' ) ) ? '640' : $sec;
                            $sec = ( stripos( $p, 'readme.txt' ) ) ? '640' : $sec;
                            $sec = ( stripos( $p, 'readme.md' ) ) ? '640' : $sec;
                            $sec = ( stripos( $p, 'changelog.txt' ) ) ? '640' : $sec;
                            $sec = ( stripos( $p, 'changelog.md' ) ) ? '640' : $sec;
                            $sec = ( stripos( $p, 'license.txt' ) ) ? '640' : $sec;

                            if ( $current == $min || $current == $sec ) {

                                if ( $min == $sec ) {

                                    $status = 'secure';

                                } else {

                                    $status = ( $current == $sec ) ? 'secure' : 'good';

                                    if ( $status == 'good' && ( $this->is_pro() || ( $tab != 'tab_plugins' && $tab != 'tab_theme' ) ) ) {

                                        $good['files'] = $good['files'] + 1;
                                        $notice_class = 'notice-info';

                                    }

                                }
                                
                            } else {

                                // Ceiling
                                $status = ( $owner > 6 || $group > 4 || $world > 4 ) ? 'bad' : 'ok';

                                // Floor
                                $status = ( $owner < 4 || $group < 0 || $world < 0 ) ? 'bad' : $status;

                                if ( $status == 'bad' ) {

                                    $bad['files'] = $bad['files'] + 1;
                                    $notice_class = 'notice-error';

                                } else {

                                    $ok['files'] = $ok['files'] + 1;
                                    $notice_class = 'notice-warning';

                                }

                            } // $current
                            
                        } // $is_dir

                        // Create Standard Option
                        $option_min = ( $status != 'good' && $min != $current ) ? '<option value="' . $min . '|' . $rel_path . '">' . $min . ' - Standard</option>' : false;
                        
                        if ( $this->is_pro() || ( $tab != 'tab_plugins' && $tab != 'tab_theme' ) ) {
                            
                            // Create Secure Option
                            $option_sec = ( $status != 'secure' ) ? '<option value="' . $sec . '|' . $rel_path . '">' . $sec . ' - Secure</option>' : false;
                            $option_sec = ( $min == $sec ) ? false : $option_sec;

                        } else {

                            $option_sec = false;

                        }
                        
                        if ( $option_min || $option_sec ) {

                            $file_count++;

                            // Create Select Dropdown
                            $select = '<select name="file-' . $file_count . '"><option value="-1"> -- Select One -- </option>';
                            $select .= ( $option_min ) ? $option_min : '';
                            $select .= ( $option_sec ) ? $option_sec : '';
                            $select .= '</select>';

                        } else { 

                            $select = '-';

                        } // $option_min

                    } // $rel_path

                    $html .=    '<tr class="' . $notice_class . '">
                                    <td class="check-column">' . $rel_path . '</td>
                                    <td class="check-column" style="text-align: center;">' . $type . '</td>
                                    <td class="check-column" style="text-align: center;">' . $owner . $group . $world . '</td>
                                    <td class="check-column ' . strtolower( $status ) . '" style="text-align: center;">' . ucwords( $status ) . '</td>';
                    $html .= ( $rel_path == '/' ) ? '<td class="check-column" style="text-align: center;"> - </td>' : '<td class="check-column" style="text-align: center;">' . $select . '</td>';
                    $html .=    '</tr>';

                } // file_exists()

            } // foreach()

        } else {

            $html .= '<tr><td colspan="5">Error: There were not any files to check.</td></tr>';

        } // is_array()

        // Show Update Permissions Button
        $html .= '<tr><td colspan="4"></td><td>' . $this->button('Update Permissions') . '</td></tr>';

        $html .= '</table>';

        // Display Notices
        $this->display_notices_perms( $good, $ok, $bad );

        // Memory Cleanup
        unset( $paths, $p, $info, $permissions, $perm, $owner, $group, $world, $type, $rec, $status, $good, $ok, $bad );

        return $html;

    } // display_permissions_table()


    /**
     * Grabs all the files and folders for a provided directory. It scans in-depth by default.
     * @since  1.0.3
     */ 
    private function get_dir_files( $folder, $deep = true ) {

        // Scan All Files In Directory
        $files = scandir( $folder );
        $results = array();

        foreach ( $files as $file ) {

            if( in_array( $file, array('.','..') ) ) {

                if ( $file == '.' ) {

                    $abspath = $folder . '/';

                    if ( $abspath == ABSPATH ){
                        $results[] = ABSPATH;
                    } else {
                        $results[] = $folder;
                    }

                } // $file
            
            } elseif ( is_dir( $folder . '/' . $file ) ) {
                
                if ( $deep ) {

                    //It is a dir; let's scan it
                    $array_results = $this->get_dir_files( $folder . '/' . $file );
                    
                    foreach ( $array_results as $r ){

                        $results[] = $r;

                    }// foreach()

                } else {

                    // Add folder to list and do not scan it.
                    $results[] = $folder . '/' . $file;

                } // $deep

            } else {

                //It is a file
                $results[] = $folder . '/' . $file;

            }

        } // foreach()

        // Memory Cleanup
        unset( $folder, $deep, $files, $file, $abspath, $array_results );

        return $results;

    } // get_dir_files()

    /**
     * Fix File Permissions
     * @since  1.1.0
     */
    private function fix_permissions() {

        global $SecuritySafe;

        if ( isset( $_POST ) && ! empty( $_POST ) ) {

            if ( isset( $_GET['tab'] ) && in_array( $_GET['tab'], array( 'core', 'theme', 'plugins', 'uploads' ) ) ) {

                // Add Notice To Look At Process Log
                $SecuritySafe->messages[] = array( 'Please review the Process Log below for details.' , 1, 0 );

                // Sanitize $_POST Before We Do Anything
                $post = filter_var_array( $_POST, FILTER_SANITIZE_STRING );

                foreach ( $post as $name => $value ) {

                    $v = explode( '|', $value );

                    if( strpos( $name, 'file-' ) === false || $v[0] == '0' ) {

                        // Pass On This One
                        
                    } else {

                        $this->set_permissions( $v[1], $v[0] );

                    } // strpos()

                } // foreach()

                // Cleanup Memory
                unset( $post, $name, $value, $v );

            } // $_GET['tab']

        } // $_POST

    } // fix_permissions()


    /** 
     * Set Permissions For File or Directory
     * @param $path Absolute path to file or directory
     * @param $perm Desired permissions value 3 chars
     */
    private function set_permissions( $path, $perm ) {
        
        // Get File Path With A Baseline Sanitization
        $path = esc_url( $path );

        // Cleanup Path ( bc WP doesn't have a file path sanitization filter )
        $path = str_replace( array( ABSPATH, 'http://', 'https://', '..', '"', "'", ')', '(' ), '', $path );

        // Add ABSPATH
        $path = ABSPATH . $path;

        // Cleanup Path Again..
        $path = str_replace( array( '/./', '////', '///', '//' ), '/', $path );

        // Relative Path (clean)
        $rel_path = str_replace( ABSPATH, '/', $path );

        // Get Permissions
        $perm = sanitize_text_field( $perm );

        $result = false;

        if ( file_exists( $path ) ) {

            // Permissions Be 3 Chars In Length
            if ( strlen( $perm ) == 3 ) {

                // Perm Value Must Be Octal; Not A String 
                if      ( $perm == '775' ) {  $result = chmod( $path, 0775 ); }
                elseif  ( $perm == '755' ) {  $result = chmod( $path, 0755 ); }
                elseif  ( $perm == '711' ) {  $result = chmod( $path, 0711 ); }
                elseif  ( $perm == '644' ) {  $result = chmod( $path, 0644 ); }
                elseif  ( $perm == '640' ) {  $result = chmod( $path, 0640 ); }
                elseif  ( $perm == '604' ) {  $result = chmod( $path, 0604 ); }
                elseif  ( $perm == '600' ) {  $result = chmod( $path, 0600 ); }

                $result = true;

            } // strlen()

        } else {

            $this->messages[] = array( 'NOT EXIST file: ' . $path , 3, 0 );

        } // file_exists()

        if ( $result ) {

            $this->messages[] = array( 'SUCCESS: File permissions were successfully updated to ' . $perm . ' for file: ' . $rel_path, 0, 0 );
            
        } else {

            $this->messages[] = array( 'ERROR: File permissions could not be updated to ' . $perm . ' for file: ' . $rel_path . '. Please contact your hosting provider for assistance.', 3, 0 );

        } // $result

        // Cleanup Memory
        unset( $path, $rel_path, $perm, $result );

    } // set_permissions()



    /**
     * Displays the current status of files that are not secure.
     * @since  1.1.4
     */ 
    private function display_notices_perms( $good, $ok, $bad ) {

        global $SecuritySafe;

        // Display Notices
        if ( 
            ( isset( $good['dirs'] ) && $good['dirs'] > 0 ) || 
            ( isset( $good['files'] ) && $good['files'] > 0 ) 
        ) {

            $message = 'You have ';

            if ( $good['dirs'] > 0 ) {

                $plural = ( $good['dirs'] > 1 ) ? ' directories' : ' directory';

                // Add Notice To Look At Process Log
                $message .= $good['dirs'] . $plural;

            }

            if ( $good['dirs'] > 0 && $good['files'] > 0 ) {

                $message .= ' and ';

            }

            if ( $good['files'] > 0 ) {

                $plural = ( $good['files'] > 1 ) ? ' files' : ' file';

                // Add Notice To Look At Process Log
                $message .= $good['files'] . $plural;
            
            }

            $message .= ' that could be more secure.';

            $SecuritySafe->messages[] = array( $message , 1, 1 );

        } // endif $good

        if ( 
            ( isset( $ok['dirs'] ) && $ok['dirs'] > 0 ) || 
            ( isset( $ok['files'] ) && $ok['files'] > 0 ) 
        ) {

            $message = 'You have ';

            if ( isset( $ok['dirs'] ) && $ok['dirs'] > 0 ) {

                $plural = ( $ok['dirs'] > 1 ) ? ' directories' : ' directory';

                // Add Notice To Look At Process Log
                $message .= $ok['dirs'] . $plural;

            }

            if ( isset( $ok['dirs'] ) && isset( $ok['files'] ) && $ok['dirs'] > 0 && $ok['files'] > 0 ) {

                $message .= ' and ';

            }

            if ( isset( $ok['files'] ) && $ok['files'] > 0 ) {

                $plural = ( $ok['files'] > 1 ) ? ' files' : ' file';

                // Add Notice To Look At Process Log
                $message .= $ok['files'] . $plural;
            
            }

            $message .= ' with safe but unique permissions. This might cause functionality issues.';

            $SecuritySafe->messages[] = array( $message , 2, 1 );

        } // endif $ok

        if ( 
            ( isset( $bad['dirs'] ) && $bad['dirs'] > 0 ) || 
            ( isset( $bad['files'] ) && $bad['files'] > 0 ) 
        ) {

            $message = 'You have ';

            if ( isset( $bad['dirs'] ) && $bad['dirs'] > 0 ) {

                $plural = ( $bad['dirs'] > 1 ) ? ' directories' : ' directory';
                $message .=  $bad['dirs'] . ' vulnerable' . $plural;

            }

            if ( isset( $bad['dirs'] ) && isset( $bad['files'] ) && $bad['dirs'] > 0 && $bad['files'] > 0 ) {

                $message .= ' and ';

            }

            if ( isset( $bad['files'] ) && $bad['files'] > 0 ) {

                $plural = ( $bad['files'] > 1 ) ? ' files' : ' file';

                // Add Notice To Look At Process Log
                $message .= $bad['files'] . ' vulnerable' . $plural;
                
            }

            $message .= '.';

            $SecuritySafe->messages[] = array( $message , 3, 0 );

        } // endif $bad

        // PHP Notices
        if ( isset( $ok['php'] ) && is_array( $ok['php'] ) ) {

            $PHP_major = substr( $ok['php'][1], 0, 1 );
            $PHP_changelog = 'https://secure.php.net/ChangeLog-' . $PHP_major . '.php';
            $message = 'You have PHP version ' . $ok['php'][0] . ' and it needs to be updated to version ' . $ok['php'][1] . ' or higher. If version ' . $ok['php'][1] . ' was released more than 30 days ago and there is more than a 90-day timespan between PHP version ' . $ok['php'][0] . ' and ' . $ok['php'][1] . ' (<a href="' . $PHP_changelog . '" target="_blank">see changelog</a>), contact your hosting provider to upgrade PHP.';

            $SecuritySafe->messages[] = array( $message , 2, 0 );
        
        } // $bad['php']

        if ( isset( $bad['php'] ) && is_array( $bad['php'] ) ) {

            $message = 'You are using PHP version ' . $bad['php'][0] . ', which is no longer supported and has critical vulnerabilities. Immediately contact your hosting company to upgrade PHP to version ' . $bad['php'][1] . ' or higher.';

            $SecuritySafe->messages[] = array( $message , 3, 0 );
        
        } // $bad['php']
                    
        // Display Notices Created In This File
        $SecuritySafe->display_notices();

        // Cleanup Memory
        unset( $good, $ok, $bad, $message, $plural );
    
    } // display_notices_perms()


} // AdminPageFiles()
