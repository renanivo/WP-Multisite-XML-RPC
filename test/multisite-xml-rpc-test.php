<?php
require_once "PHPUnit/Framework.php";
require_once "MockPress/mockpress.php";
require_once "src/multisite-xml-rpc.php";

class MultisiteXmlRpcTest extends PHPUnit_Framework_TestCase {

	function setUp() {
		global $wp_xmlrpc_server, $wpdb;
		$wp_xmlrpc_server = $this->getMock(
			'wp_xmlrpc_server', array('escape', 'login')
		);
		
		$wp_xmlrpc_server->expects($this->once())
			->method('escape')
			->will($this->returnArgument(0));

		$wpdb = $this->getMock(
			'wpdb', array('get_results', 'prepare')
		);

		_reset_wp();
	}

	function testCreateBlogShouldReturnInteger() {
		global $wp_xmlrpc_server, $wpdb;

		$wpdb->expects($this->once())
			->method('get_results')
			->will($this->returnValue(array()));

		$wp_xmlrpc_server->expects($this->once())
			->method('login')
			->will($this->returnValue(true));

		$blog_id = msxmlrpc_create_blog(array(
			'test',
			'test',
			array(
				'domain'  => 'example.com',
				'path'    => "path",
				'title'   => "Title ",
				'user_id' => "user@example.com",
			),
		));

		$this->assertTrue(is_int($blog_id));
	}

	function testLoginFailShouldRaiseError() {
		global $wp_xmlrpc_server, $wpdb;

		$this->getMock("IXR_Error");

		$wp_xmlrpc_server->expects($this->once())
			->method('login')
			->will($this->returnValue(false));

		$wp_xmlrpc_server->error = "Something Happened";

		$blog_id = msxmlrpc_create_blog(array(
			'test',
			'test',
			array(
				'domain'  => 'example.com',
				'path'    => "path",
				'title'   => "Title ",
				'user_id' => "user@example.com",
			),
		));

		$this->assertTrue(is_object($blog_id));
		$this->assertEquals(get_class($blog_id), "IXR_Error");
	}

}
