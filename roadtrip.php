<?php
/*
Plugin Name:    Roadtrip
Plugin URI:     http://theloniuspope.com/roadtrip/
Description:    Displays Google maps of your road trip itinerary
Version:        0.1a
Author:         Shea Goyette
Author E-mail:  shea.goyette@gmail.com
Author URI:     http://theloniuspope.com
License:        GPL2

Copyright 2012 Shea Goyette

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 or later
as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

class RoadtripView {
	private static $initialised = false;
	private static $googleMapsKey = ''; // TODO: store key in wordpress database
	public static $googleMapsURL = '';

	static function roadtrip_init() {
		if (!self::$initialised) {
			// Append Google Maps key and other parameters
			self::$googleMapsURL = 'http://maps.googleapis.com/maps/api/js'.
			                       '?key='.self::$googleMapsKey.
			                       '&sensor=false';

			// Register Roadtrip javascript
			add_action('wp_footer', array( __class__, 'register_scripts'));
			self::$initialised = true;
		}
	}

	static function register_scripts() {
		wp_register_script('google-maps', self::$googleMapsURL, false, false, true);
		wp_print_scripts('google-maps');
		wp_register_script('roadtrip', plugins_url('roadtrip.js', __FILE__ ), false, false, true);
		wp_print_scripts('roadtrip');
	}

	function display_map($attr) {
		// Display roadtrip map using Google Maps API
		return '<div id="roadtrip-map" style="width:320px; height:240px"></div>';
	}
}

// If not in Wordpress, test Roadtrip functions
if (!is_callable('add_filter')) {
	echo '<html>';
	echo '<head>';
	echo '<title>Roadtrip Plug-in for Wordpress</title>';
	echo '</head>';
	echo '<body onload="initialize()">';

	RoadtripView::roadtrip_init();
	$view = new RoadtripView();
	$view->display_map();
	unset($view);

	echo '</body>';
	echo '</html>';

	exit(0);
}

RoadtripView::roadtrip_init();
$view = new RoadtripView();
// TODO: display_map() function needs to create new RoadtripView object
add_shortcode('roadtrip-map', array($view, 'display_map'));
unset($view);

?>
