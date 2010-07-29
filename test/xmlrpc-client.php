<?php
/**
 * An as easy as possible XML-RPC client for test purposes
 * @author Renan Ivo <renanivom@gmail.com>
 */

class XmlRpcClient {
	private $server_domain;

	/**
	 * The class constructor
	 * @param string $server_domain the domain of the XML-RPC Server
	 */
	function __construct($server_domain) {
		$this->server_domain = $server_domain;
	}

	/**
	 * sends a request to the server
	 * @param string $method the server's method name
	 * @param array $params  the method's parameters
	 * return array the server response
	 */
	function request($method, $params) {
		$context = stream_context_create(array('http' => array(
			'method'  => "POST",
			'header'  => "Content-Type: text/xml",
			'content' => xmlrpc_encode_request($method, $params),
		)));

		return xmlrpc_decode(file_get_contents(
			"http://" . $this->server_domain . "/xmlrpc.php",
			false,
			$context
		));
	}
}
