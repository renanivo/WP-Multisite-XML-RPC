=== Multisite XML-RPC ===
Contributors: renanivo
Tags: multisite, xmlrpc
Requires at least: 3.0
Tested up to: 3.0
Stable tag: trunk

Adds some Multisite-specific functions to WordPress' XML-RPC interface.

== Description ==

Adds some Multisite-specific functions to WordPress' XML-RPC interface (http://yourdomain.com/xmlrpc.php).

Using this plugin, you can create a blog, update the archived status and get a blog ID using a remote software (another server, a desktop software, a mobile software...)

The functions enabled by this plugin are:

* Create Blog (ms.CreateBlog)
* Get Blog Id (ms.GetBlogId)
* Update Archived (ms.UpdateArchived)

refer to the [wiki](http://wiki.github.com/renanivo/WP-Multisite-XML-RPC/) for further documentation

== Frequently Asked Questions ==

= What is XML-RPC? =

According to [Wikipedia](http://en.wikipedia.org/wiki/Xml-rpc), XML-RPC is a remote procedure call (RPC) protocol which uses XML to encode its calls and HTTP as a transport mechanism. Refer to the [WordPress Wiki](http://codex.wordpress.org/XML-RPC_Support) for further explanation about WP's XML-RPC capabilities.

= My plugin is enabled but is not working, what should I do? =

Maybe the remote publishing function is not enabled. To enable it, chek 'Enable the WordPress, Movable Type, MetaWeblog and Blogger XML-RPC publishing protocols' through 'Writing' menu and click 'Save Changes' (step 3 in *Installation*).

== Installation ==
1. Upload `multisite-xml-rpc.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Check 'Enable the WordPress, Movable Type, MetaWeblog and Blogger XML-RPC publishing protocols' through 'Writing' menu and click 'Save Changes'
1. Start sending XML-RPC requests to the new functions enabled
