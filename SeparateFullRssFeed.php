<?php

/*
Plugin Name: Separate Full RSS Feed
Plugin URI: 
Description: Create a separate Full RSS Feed
Author: Magno
Version: 1.0
Author URI: http://nav-magno.be
License: GPL2
*/

/*  Copyright 2013  Magno  (email : magno@nav-magno.be)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General License for more details.

    You should have received a copy of the GNU General License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('Separate_Full_RSS_Feed'))
{
	class Separate_Full_RSS_Feed
	{
		function __construct()
		{
			// register actions
			add_action('admin_init', array( &$this, 'plugin_init')); 
			add_action('admin_menu', array( &$this, 'plugin_menu'));
			add_filter('init', array(&$this, 'init_custom_feed'));
			
		} // end construct
		
		static function activate()
		{
			register_uninstall_hook(__FILE__,'plugin_uninstall');
		} // end activate

		static function deactivate()
		{
			// do nothing
		} // end deactivate
		function init_custom_feed()
		{
			add_feed('fullrss', array( &$this, 'full_rss'));
			add_action('generate_rewrite_rules', array(&$this, 'plugin_rewrite_rules'));
			//Ensure the $wp_rewrite global is loaded
			global $wp_rewrite;
			//Call flush_rules() as a method of the $wp_rewrite object
			$wp_rewrite->flush_rules();
			//http://wordpress.org/support/topic/creating-a-custom-rss-feed
			//http://plugins.svn.wordpress.org/feed-wrangler/tags/0.3.2/feed-wrangler.php
			//http://xplus3.net/2008/10/30/custom-feeds-in-wordpress/
		} // end init_custom_feed
		
		function plugin_rewrite_rules( $wp_rewrite )
		{
			$new_rules = array(
				'feed/(.+)' => 'index.php?feed='.$wp_rewrite->preg_index(1)
			);
			$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
		} // end plugin_rewrite_rules
		
		function full_rss() {
			load_template(sprintf("%s/templates/feed-fullrss.php", dirname(__FILE__)));
		} // end full_rss
		
		function plugin_init()
		{
			$this->init_settings();
		} // end plugin_init
		
		function init_settings()
		{
			//register_setting('separate_full_rss_feed-group', 'rss_slug'); 			
		} // end init_settings
		
		function plugin_menu()
		{
			add_options_page( 'Separate Full RSS Feed Settings', 'Separate Full RSS Feed', 'manage_options', 'separate-full-rss-feed-settings', array( &$this, 'plugin_settings_page'));		
		} // end plugin_menu
		
		function plugin_settings_page()
		{
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
		} // end plugin_settings_page
		
		function plugin_uninstall()
		{
			//add_feed('fullrss','do_feed_rss2');
			flush_rewrite_rules();
			//Ensure the $wp_rewrite global is loaded
			global $wp_rewrite;
			//Call flush_rules() as a method of the $wp_rewrite object
			$wp_rewrite->flush_rules();
		} // end plugin_uninstall
		
	} // end class
} // end if class_exists

if(class_exists('Separate_Full_RSS_Feed')) 
{ 
	// Installation and uninstallation hooks 
	register_activation_hook(__FILE__, array('Separate_Full_RSS_Feed', 'activate')); 
	register_deactivation_hook(__FILE__, array('Separate_Full_RSS_Feed', 'deactivate')); 
	
	// instantiate the plugin class 
	$Separate_Full_RSS_Feed = new Separate_Full_RSS_Feed(); 
	
	// add a link to the settings page on the plugin page
	if(isset($Separate_Full_RSS_Feed))
	{
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=separate-full-rss-feed-settings">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin",'plugin_settings_link');
	} // if isset
} 
?>