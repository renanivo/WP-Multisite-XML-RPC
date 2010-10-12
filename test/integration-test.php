<?php
require_once "PHPUnit/Framework.php";
require_once dirname(dirname(__FILE__)) . "/xmlrpc-client.php";

class IntegrationTest extends PHPUnit_Framework_TestCase {
	public $xrClient;

	function setUp() {
		$this->xrClient = new XmlRpcClient("http://localhost/xmlrpc.php");
	}

	function testCreateBlog() {
		$blog_id = $this->xrClient->request("ms.CreateBlog", array(
			'admin',
			'password',
			array(
				'domain'  => 'localhost',
				'path'    => "/p" . rand(),
				'title'   => "T" . rand(),
				'user_id' => "user" . rand() . "@example.com",
			),
		));
		$this->assertTrue(is_int($blog_id));
		$this->assertGreaterThan(1, $blog_id);
	}

	function testGetBlogId() {
		$blog_id = $this->xrClient->request("ms.GetBlogId", array(
			'admin',
			'password',
			array(
				'domain' => "localhost",
				'path'   => "",
			),
		));
		$this->assertTrue(is_numeric($blog_id));
	}

	function testUpdateArchived() {
		$archived_status = $this->xrClient->request("ms.UpdateArchived", array(
			'admin',
			'password',
			array(
				'id'       => 1,
				'archived' => 0,
			)
		));
		$this->assertEquals(0, $archived_status);
	}
}
