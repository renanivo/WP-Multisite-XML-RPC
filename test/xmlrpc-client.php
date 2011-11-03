<?php 
/** 
 * A small Xml-Rpc Client class as simple as it can be. 
 * It uses PHP's magic overloads for a simple worklow. 
 * 
 * Example: 
 * 
 * $rpc  = new XmlRpcClient( "http://www.example.com/xmlrpc/" ); 
 * $data = $rpc->namespace->namespace->method(); // Will produce a method called namespace.namespace.method 
 * 
 * var_dump( $data ); 
 */ 
class XmlRpcClient 
{ 
    protected $_url; 
    protected $_namespace; 
    protected $_clients; 
    
    public function __construct( $url, $namespace = '' ) 
    { 
        $this->_url       = (string)$url; 
        $this->_namespace = (string)$namespace; 
        $this->_clients   = array(); 
    } 
    
    public function __get( $namespace ) 
    { 
        if( !key_exists( $namespace, $this->_clients ) ) 
            $this->_clients[ $namespace ] = new XmlRpcClient( $this->_url, strlen( $this->_namespace ) > 0 ? "$this->_namespace.$namespace" : $namespace ); 
        
        return $this->_clients[ $namespace ]; 
    } 
    
    public function __call( $method, array $parameters = array() ) 
    { 
        $request = xmlrpc_encode_request( strlen( $this->_namespace ) > 0 ? "$this->_namespace.$method" : $method, $parameters ); 
        $context = stream_context_create( 
            array( 
                'http' => array( 
                    'method'  => "POST", 
                    'header'  => "Content-Type: text/xml", 
                    'content' => $request 
                ) 
            ) 
        ); 
        
        $file       = file_get_contents( $this->_url, false, $context ); 
		$response = xmlrpc_decode( $file ); 
        
        if( is_null($response) ) 
			throw new XmlRpcClientException( array( 'faultString' => 'Invalid response from ' . $this->_url, 'faultCode' => 999 ) ); 
        
        if( @xmlrpc_is_fault( $response ) ) 
            throw new XmlRpcClientException( $response ); 
        
        return $response; 
    } 
} 

/** 
 * Will be thrown by XmlRpcClient on faults. 
 */ 
class XmlRpcClientException extends Exception 
{ 
    public function __construct( $response ) 
    { 
        parent::__construct( $response[ 'faultString' ], $response[ 'faultCode' ] ); 
    } 
}
