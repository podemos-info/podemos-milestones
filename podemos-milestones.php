<?php
/**
* Plugin Name: Podemos Milestones
* Plugin URI: 
* Description: Milestones management system for Podemos websites.
* Version: 0.0
* Author: PODEMOS
* Author URI: https://podemos.info
* License:
*
*
* Required plugins for better performance:
*
*  - Advanced Custom Fields
*    https://es.wordpress.org/plugins/advanced-custom-fields/
*
*  - Advanced Custom Fields: Date and Time Picker
*    https://es.wordpress.org/plugins/acf-field-date-time-picker/
*/

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'pm_register_milestone_posttype.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'pm_shortcodes.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'pm_widget.php';

