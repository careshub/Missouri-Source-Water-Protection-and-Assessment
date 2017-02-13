<?php
/**
 * Community Commons MoCWP
 *
 * @package   Community_Commons_MoCWP
 * @author    dcavins
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2017 Community Commons
 */

/**
 * Generate the water system search form via shortcode.
 *
 * @since   1.0.0
 * @param   array $atts Shortcode attributes
 *
 * @return  html Form.
 */
function mocwp_form_shortcode( $atts ) {
    $a = shortcode_atts( array(), $atts );
    ob_start();
    mocwp_form();
    return ob_get_clean();
}
add_shortcode( 'mocwp_form', 'mocwp_form_shortcode' );