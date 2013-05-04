<?php

/*
|--------------------------------------------------------------------------
| Sparky - Main theme class
|--------------------------------------------------------------------------
| 
| This class will provide simplified methods to work with some of the
| functionality that WordPress provides.
| 
| The aim of this class is to act as a helper.
| 
*/

class Sparky {
	
	/**
	 * Initialise and run any kind that needs to be called in the beginning.
	 *
	 * @return void
	 */
	public static function init()
	{
		add_theme_support( 'menus' );
		add_theme_support( 'post-thumbnails' );
	}
	
	
	
	/**
	 * Adds custom image sizes so WordPress is aware and will resize into
	 * those sizes when an image is uploaded.
	 *
	 * @param array $sizes Array is expected to be formatted as such:
	 *                     'size-name' => '600x200'
	 */
	public static function add_custom_image_sizes( $sizes )
	{
		if ( empty($sizes) ) return false;
		
		foreach ( $sizes as $name => $size ) {
			list( $w , $h ) = explode( 'x' , $size );
			add_image_size( $name , $w , $h , true );
		}
	}
	
	
	
	/**
	 * Returns a menu based on what you have named it in the WP Admin section.
	 *
	 * @param  string $name
	 *
	 * @return void string
	 */
	public static function menu( $name )
	{
		$args = array(
			'echo' => false,
			'menu' => $name
		);
		
		return wp_nav_menu( $args );
	}
}
