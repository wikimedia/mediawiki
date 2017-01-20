<?php

/**
 * Minimal set of classes necessary for Redis classes to be happy.
 * @codingStandardsIgnoreFile
 */

/**
 * @method  eval( $script, $args = array(), $numKeys = 0 )
 */
class Redis{

	const OPT_SERIALIZER        = 1;
	const OPT_READ_TIMEOUT = 3;

	const SERIALIZER_NONE       = 0;
	const SERIALIZER_PHP        = 1;
	const SERIALIZER_IGBINARY   = 2;

	const PIPELINE = 2;

	/**
	 * @param string    $host       can be a host, or the path to a unix domain socket
	 * @param int       $port       optional
	 * @param float     $timeout    value in seconds (optional, default is 0.0 meaning unlimited)
	 * @return bool                 TRUE on success, FALSE on error.
	 */
	public function connect( $host, $port = 6379, $timeout = 0.0 ) {}

	/**
	 * @param string    $host       can be a host, or the path to a unix domain socket
	 * @param int       $port       optional
	 * @param float     $timeout    value in seconds (optional, default is 0 meaning unlimited)
	 * @return bool                 TRUE on success, FALSE on ertcnror.
	 */
	public function pconnect( $host, $port = 6379, $timeout = 0.0 ) {}

	/**
	 * @param   string  $password
	 * @return  bool:   TRUE if the connection is authenticated, FALSE otherwise.
	 */
	public function auth( $password ) {}

	/**
	 * @param   string  $name    parameter name
	 * @param   string  $value   parameter value
	 * @return  bool:   TRUE on success, FALSE on error.
	 */
	public function setOption( $name, $value ) {}

	/**
	 * @return  string  A string with the last returned script based error message, or NULL if there is no error
	 */
	public function getLastError() {}

	/**
	 * @return bool true
	 */
	public function clearLastError() {}

	/**
	 * @param   string  $scriptSha
	 * @param   array   $args
	 * @param   int     $numKeys
	 * @return  mixed. @see eval()
	 */
	public function evalSha( $scriptSha, $args = array(), $numKeys = 0 ) {}
	
}

if( !class_exists( 'RedisException' ) ) {
	class RedisException extends Exception {}
}
