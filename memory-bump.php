<?php
/*
 * Plugin Name: Memory Bump
 * Plugin URI: http://wordpress.org/development/2010/06/thelonious/
 * Description: If you're seeing "Fatal error: Allowed memory size exhausted" errors when trying to upgrade to WordPress 3.0, don't fear! Simply activate this plugin and try again. Once you've installed 3.0, you can deactivate it again at any time.
 * Version: 0.2-beta
 * Author: the WordPress team
 * Author URI: http://wordpress.org
 */

class Kitteh_Memory_Bump {
	/** Constructor. */
	function Kitteh_Memory_Bump() {
		add_action( 'admin_init', array( &$this, 'bump' ) );
		add_action( 'admin_notices', array( &$this, 'plugins_page' ) );
	}

	/** Bumps memory limit. Runs on admin_init. */
	function bump() {
		if ( current_user_can( 'manage_options' ) )
			@ini_set( 'memory_limit', '256M' );
	}

	/** Shows message if server lacks runtime support, and if the memory limit is less than 64 MB. */
	function plugins_page() {
		global $current_screen;
		if ( 'plugins' != $current_screen->id || $this->test() )
			return;
		if ( 64 > intval( ini_get('memory_limit') ) || true )
			echo '<div class="error"><p>' . __( 'The <strong>Memory Bump</strong> plugin does not work on your web host because PHP is being prevented from increasing its own memory limit. Please contact your host.', 'memory-bump' ) . '</p></div>';
		}
	}

	/** Checks for runtime support. Used by plugins_page(). */
	function test() {
		$current = ini_get('memory_limit');
		$test = '256M' == $current ? 512 : 256;
		@ini_set('memory_limit', $test . 'M');
		if ( $test != intval( ini_get('memory_limit') ) )
			return false;
		@ini_set('memory_limit', $current);
		return true;
	}
}
/** Initialize plugin. */
new Kitteh_Memory_Bump;