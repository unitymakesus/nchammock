<?php

namespace SecuritySafe;

// Prevent Direct Access
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * @package SecuritySafe
 * @version 1.1.13
 */

/*
 * Plugin Name: Security Safe
 * Plugin URI: https://sovstack.com/security-safe
 * Description: Security Safe - Security, Hardening, Auditing & Privacy
 * Author: Sovereign Stack, LLC
 * Author URI: https://sovstack.com
 * Version: 1.1.13
 * Text Domain: security-safe
 * Domain Path:  /languages
 * License: GPLv3 or later
 */

/*
Copyright (C) 2018  Sovereign Stack, LLC (email : support@sovstack.com)
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

$SecuritySafe = array();

// Base Plugin
$SecuritySafe['name'] = 'Security Safe';
$SecuritySafe['version'] = '1.1.13';
$SecuritySafe['slug'] = 'security-safe';
$SecuritySafe['options'] = 'securitysafe_options';
$SecuritySafe['file'] = __FILE__;
$SecuritySafe['dir'] = __DIR__;
$SecuritySafe['dir_admin'] = __DIR__ . '/admin';
$SecuritySafe['dir_common'] = __DIR__ . '/common';
$SecuritySafe['dir_lang'] = __DIR__ . '/languages';
$SecuritySafe['url'] = plugin_dir_url( __FILE__ );
$SecuritySafe['url_author'] = 'https://sovstack.com/';
$SecuritySafe['url_more_info'] = 'https://sovstack.com/security-safe/';

// Pro Addon
$SecuritySafe['version_pro'] = false;
$SecuritySafe['slug_pro'] = $SecuritySafe['slug'] . '-pro';
$SecuritySafe['file_pro'] = $SecuritySafe['slug_pro'] . '.php';
$SecuritySafe['dir_pro'] = dirname ( __DIR__ ) . '/' . $SecuritySafe['slug_pro'];
$SecuritySafe['dir_admin_pro'] = $SecuritySafe['dir_pro'] . '/admin';
$SecuritySafe['dir_common_pro'] = $SecuritySafe['dir_pro'] . '/common';
$SecuritySafe['dir_lang_pro'] = $SecuritySafe['dir_pro'] . '/languages';
$SecuritySafe['url_more_info_pro'] ='https://sovstack.com/security-safe/pro/';

// Autoload
require_once( __DIR__ . '/vendor/autoload.php' );

// Init Plugin
add_action( 'init', __NAMESPACE__ . '\\Plugin::init' );

// Cleanup Plugin
add_action( 'shutdown', __NAMESPACE__ . '\\Plugin::shutdown' );
