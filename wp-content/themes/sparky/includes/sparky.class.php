<?php

/*
|--------------------------------------------------------------------------
| Sparky - Main theme class
|--------------------------------------------------------------------------
| 
| This class will provide simplified methods to work with some of the
| functionality that WordPress provides.
| 
| The aim of this class is to act as a helper, so feel free to modify it.
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
		
		// Disable admin bar.
		add_filter( 'show_admin_bar' , function() { return false; } );
		
		// Disable update message.
		self::disable_update_message();
		
		if ( defined( 'SMTP_HOST' ) && !empty( SMTP_HOST ) )
		{
			self::setupSMTP();
		}
	}
	
	
	
	/**
	 * Outputs/returns a string and allows you to perform a find and replace for special
	 * keywords. For example:
	 * 		$output = '<h1>{title}</h1>';
	 * 		$replacements = [ '{title}' => 'My Title' ];
	 * 		Sparky::render( $output , $replacements );
	 *
	 * @param  string  $output
	 * @param  array   $replacements
	 * @param  boolean $echo
	 *
	 * @return mixed
	 */
	public static function render( $output , $replacements = [] , $echo = true )
	{
		$find = array_keys( $replacements );
		$replace = array_values( $replacements );
		
		$html = str_replace( $find , $replace , $output );
		
		if ( !$echo ) return $html;
		
		echo $html;
	}
	
	
	
	/**
	 * Outputs a generic title.
	 * Edit this as to your liking.
	 */
	public static function title( $echo = true )
	{
		$title = is_front_page() ? 'Home | ' : wp_title( '|' , false , 'right' );
		$title .= get_bloginfo( 'name' );
		
		if ( !$echo ) return $title;
		
		echo $title;
	}
	
	
	
	/**
	 * Outputs a generic description.both
	 * Edit this as to your liking.
	 */
	public static function description( $echo = true )
	{
		// Maximum length of the meta description. Recommended: 155 characters.
		$max_length = 155;
		
		$description = is_single() ? single_post_title( '' , false ) : get_bloginfo('name');
		
		// If a tagline/description has been set for the blog, we'll append it to our description.
		$tagline = get_bloginfo( 'description' );
		if ( $tagline ) {
			$description .= " - $tagline";
		}
		
		// Keep the length of the description below the recommended maximum.
		if ( strlen($description) > $max_length ) {
			$description = substr( $description , 0 , $max_length-3 ) . '...';
		}
		
		if ( !$echo ) return $description;
		
		echo $description;
	}
	
	
	
	/**
	 * If configured in the wp-config file, we will override the SMTP settings
	 * that WordPress uses by default.
	 *
	 * @return void
	 */
	public static function setupSMTP()
	{
		add_action( 'phpmailer_init' , function( $mail_settings ) {
			$phpmailer =& $mail_settings;
			$phpmailer->IsSMTP();

			$phpmailer->Host     = SMTP_HOST;
			$phpmailer->Username = SMTP_USER;
			$phpmailer->Password = SMTP_PASS;
			$phpmailer->Port     = SMTP_PORT;
			$phpmailer->SMTPAuth = SMTP_AUTH;
		});
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
	 * @param  array  $args Arguments to merge with the defaults.
	 *
	 * @return void string
	 */
	public static function menu( $name , $args = [] )
	{
		$default_args = [
			'echo' => false,
			'menu' => $name
		];
		
		$args = array_merge( $default_args , $args );
		
		return wp_nav_menu( $args );
	}
	
	
	
	/**
	 * Retrieves the source for the feature image.
	 *
	 * @param  string $size
	 * @param  mixed  $post_id passing null will retrieve the current post's ID.
	 *
	 * @return mixed           returns null or a string
	 */
	public static function feature_image( $size = 'full' , $post_id = null )
	{
		if ( !has_post_thumbnail( $post_id ) ) return null;
		
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , $size );
		
		return $image[0];
	}
	
	
	
	/**
	 * Return a list of recent posts.
	 * Returns false if none are found.
	 *
	 * @param  integer $count
	 * @param  string  $type
	 *
	 * @return mixed
	 */
	public static function recent_posts( $count = 5 , $type = 'post' , $args = [] )
	{
		$default_args = [
			'numberposts' => $count,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => $type,
			'post_status' => 'publish'
		];
		
		$args = array_merge( $default_args , $args );
		
		$recent_posts = wp_get_recent_posts( $args );
		
		// Return false if no posts are found.
		if ( !count($recent_posts) ) return false;
		
		return $recent_posts;
	}
	
	
	
	/**
	 * Returns an array of pages.
	 *
	 * @param  mixed $child_of  'null' will retrieve the current page's ID
	 *                          '0' will return all the top-level pages
	 *                          '1' or more will retrieve the children of that page ID.
	 *                          By default, it will return the current page's children.
	 *
	 * @return array
	 */
	public static function pages( $child_of = null , $args = [] )
	{
		// If the child_of is specifically NULL, we will retrieve the current page's ID.
		if ( is_null( $child_of ) ) {
			$child_of = get_the_ID();
		}
		
		$default_args = [
			'sort_order' => 'ASC',
			'sort_column' => 'menu_order,post_title',
			'hierarchical' => 1,
			'child_of' => $child_of,
			'parent' => $child_of,
			'post_type' => 'page',
			'post_status' => 'publish'
		];
		
		$args = array_merge( $default_args , $args );
		
		return get_pages( $args );
	}
	
	
	
	/**
	 * Disable menu items for the admin section.
	 *
	 * @param  array $items Array of items to disable/hide.
	 *
	 * @return void
	 */
	public static function disable_admin_menu_items( $items )
	{
		if ( !is_array( $items ) || empty( $items ) ) return false;
		
		add_action( 'admin_menu' , function() use ( $items )
		{
			global $menu;
			end ($menu);
			
			// Store all the menu items that are to be disabled/hidden.
			$restricted = [];
			
			foreach ( $items as $item )
			{
				$restricted[] = __( $item );
			}
			
			while ( prev( $menu ) )
			{
				$value = explode( ' ' , $menu[ key($menu) ][ 0 ] );
				
				$value = is_null( $value[0] ) ? "" : $value[0];
				
				if ( in_array( $value , $restricted ) )
				{
					unset( $menu[ key($menu) ] );
				}
			}
		});
	}
	
	
	
	/**
	 * Disables update nag only if the DISALLOW_FILE_MODS has been set to true.
	 * The above option tells WordPress whether or not updates/plugins are allowed
	 * to be installed.
	 * 
	 * @param boolean $force_disable Ignore the check for DISALLOW_FILE_MODS and disable update message anyway.
	 *
	 * @return void
	 */
	public static function disable_update_message( $force_disable = false )
	{
		if ( $force_disable || defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS )
		{
			add_action( 'admin_menu' , function() {
				remove_action( 'admin_notices' , 'update_nag' , 3 );
			});
		}
	}
	
	
	
	/**
	 * Display pagination links for the posts page.
	 *
	 * @return void
	 */
	public static function pagination( $args = [] , $echo = true )
	{
		global $wp_query;
		
		// Get the max posts per page setting.
		$total_pages = $wp_query->max_num_pages;
		
		if ( $total_pages > 1 )
		{
			$current_page = max( 1 , get_query_var( 'paged' ) );
			
			$default_args = [
				'base'    => get_pagenum_link( 1 ) . '%_%',
				'format'  => '/page/%#%',
				'type'    => 'list',
				'current' => $current_page,
				'total'   => $total_pages
			];
			
			$args = array_merge( $default_args , $args );
			
			$pagination_links = paginate_links( $args );
			
			if ( !$echo ) return $pagination_links;
			
			echo $pagination_links;
		}
	}
	
}
