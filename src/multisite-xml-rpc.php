<?php
/*
Plugin Name: Multisite XML-RPC
Plugin URI: http://github.com/renanivo/WP-Multisite-XML-RPC
Description: Enable Multisite specific functions to XML-RPC API
Author: Renan Ivo
Author URI: http://renanivo.com.br
Version: 1.0
*/

/**
 * Checks if the request's arguments are correct and returns the function parameters
 * @param array $args  The request's arguments
 * @return mixed The function parameters or an error message
 */
function check_arguments($args) {
	global $wp_xmlrpc_server;
	$wp_xmlrpc_server->escape($args);

	if ( !$wp_xmlrpc_server->login($args[0], $args[1]) ) {
		return new IXR_Error(401, $wp_xmlrpc_server->error);
	}

	return $args[2];
}

/**
 * Returns the blog_id of given domain and path
 * @param string $domain
 * @param string $path
 * @return boolean Returns the blog_id or false if not found
 */
function get_blog_id($domain, $path) {
	global $wpdb;
	$domain_found = $wpdb->get_results($wpdb->prepare(
		"SELECT blog_id FROM wp_blogs WHERE domain = %s AND path = %s LIMIT 1",
		$domain,
		$path . '/'
	));

	if ( !count($domain_found) ) {
		return false;
	}

	return $domain_found[0]->blog_id;
}

/**
 * Creates a new blog calling wpmu_create_blog
 * the wpmu_create_blog parameters are:
 * $domain  The domain of the new blog.
 * $path    The path of the new blog.
 * $title   The title of the new blog.
 * $user_id The user id of the user account who will be the blog admin. (you can use an email instead of the user_id. If so, a new user will be created)
 * $meta    Other meta information.
 * $site_id The site_id of the blog to be created.
 *
 * @param array $args Array with username, password and wpmu_create_blog function parameters
 * @return mixed The new blog id or an error message
 */
function msxmlrpc_create_blog($args) {
	$parameters = check_arguments($args);
	if ( !is_array($parameters) ) {
		return $parameters;
	}

	// if the user_id is the user's e-mail
	if ( !is_int($parameters['user_id']) ) {
		if ( !($user_id = get_user_id_from_string($parameters['user_id'])) ) {
			$error = wpmu_validate_user_signup(
				$parameters['path'], 
				$parameters['user_id']
			);

			if ( is_wp_error($error) ) {
				return new IXR_Error(500, $error->get_error_message());
			}

			$user_id = wpmu_create_user(
				$parameters['path'],
				wp_generate_password(),
				$parameters['user_id']
			);
		}

		$parameters['user_id'] = $user_id;
	}

	if ( get_blog_id($parameters['domain'], $parameters['path']) !== false ) {
		return new IXR_Error(500, __("Site already exists."));
	}

	if ( !isset($parameters['meta']) )    $parameters['meta']    = "";
	if ( !isset($parameters['site_id']) ) $parameters['site_id'] = 1;

	return wpmu_create_blog(
		$parameters['domain'],
		$parameters['path'],
		$parameters['title'],
		$parameters['user_id'],
		$parameters['meta'],
		$parameters['site_id']
	);
}

/**
 * Returns the blog_id of given domain and path in a XML-RPC request
 * this functions parameters are:
 * $domain The blog domain
 * $path   The blog path
 *
 * @param string $args
 * @return boolean Returns the blog_id or false if not found
 */
function msxmlrpc_get_blog_id($args) {
	$parameters = check_arguments($args);
	if ( !is_array($parameters) ) {
		return $parameters;
	}

	if ( ($blog_id = get_blog_id($parameters['domain'], $parameters['path'])) !== false ) {
		return $blog_id;
	} else {
		return new IXR_Error(404, __("No sites found."));
	}
}

/**
 * Updates the archived status of a blog using the update_archived function
 * the update_archived parameters are:
 * $id       The id of the blog to be updated.
 * $archived The new archived status value.
 *
 * @param array $args Array with username, password and update_archived function parameters
 * @return integer Returns the new archived status
 */
function msxmlrpc_update_archived($args) {
	$parameters = check_arguments($args);
	if ( !is_array($parameters) ) {
		return $parameters;
	}

	return update_archived($parameters['id'], $parameters['archived']);
}

/**
 * Appends Multisite functions to the XML-RPC Interface
 * @param array $methods XML-RPC allowed methods
 * returns array
 */
function msxmlrpc_methods($methods) {
	$methods['ms.CreateBlog']     = 'msxmlrpc_create_blog';
	$methods['ms.GetBlogId']      = 'msxmlrpc_get_blog_id';
	$methods['ms.UpdateArchived'] = 'msxmlrpc_update_archived';
	return $methods;
}

add_filter('xmlrpc_methods', 'msxmlrpc_methods');
