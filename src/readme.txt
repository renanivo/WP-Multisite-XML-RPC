=== Multisite XML-RPC ===
Contributors: renanivo
Tags: multisite, xmlrpc
Requires at least: 3.0
Tested up to: 3.0
Stable tag: trunk

This plugin adds some Multisite-specific functions to WordPress' XML-RPC interface.

== Description ==

This plugin adds some Multisite-specific functions to WordPress' XML-RPC interface (http://yourdomain.com/xmlrpc.php).
The functions are:

* Create Blog (ms.CreateBlog)
* Get Blog Id (ms.GetBlogId)
* Update Archived (ms.UpdateArchived)

refer to the [wiki](http://wiki.github.com/renanivo/WP-Multisite-XML-RPC/) for further documentation

== Installation ==
1. Upload `multisite-xml-rpc.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start sending XML-RPC requests to the new functions enabled
