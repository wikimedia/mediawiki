<?php

/**
 * Minimal set of classes necessary for UserMailer to be happy. Types
 * taken from documentation at pear.php.net.
 * @codingStandardsIgnoreFile
 */

class PEAR {
	/**
	 * @param mixed $data
	 * @return bool
	 */
	public static function isError( $data ) {
	}
}

class PEAR_Error {
	/**
	 * @return string
	 */
	public function getMessage() {
	}
}

class Mail {
	/**
	 * @param string $driver
	 * @param array $params
	 * @return self
	 */
	static public function factory( $driver, array $params = [] ) {
	}

	/**
	 * @param mixed $recipients
	 * @param array $headers
	 * @param string $body
	 * @return bool|PEAR_Error
	 */
	public function send( $recipients, array $headers, $body ) {
	}
}

class Mail_smtp extends Mail {
}

class Mail_mime {
	/**
	 * @param mixed $params
	 */
	public function __construct( $params = [] ) {
	}

	/**
	 * @param string $data
	 * @param bool $isfile
	 * @param bool $append
	 * @return bool|PEAR_Error
	 */
	public function setTXTBody( $data, $isfile = false, $append = false ) {
	}

	/**
	 * @param string $data
	 * @param bool $isfile
	 * @return bool|PEAR_Error
	 */
	public function setHTMLBody( $data, $isfile = false ) {
	}

	/**
	 * @param array|null $parms
	 * @param mixed $filename
	 * @param bool $skip_head
	 * @return string|bool|PEAR_Error
	 */
	public function get( $params = null, $filename = null, $skip_head = false ) {
	}

	/**
	 * @param array|null $xtra_headers
	 * @param bool $overwrite
	 * @param bool $skip_content
	 * @return array
	 */
	public function headers( array $xtra_headers = null, $overwrite = false, $skip_content = false ) {
	}
}
