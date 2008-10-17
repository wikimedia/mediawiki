<?php

class UDP {
	/**
	 * Send some text to UDP
	 * @param string $line
	 * @param string $prefix
	 * @param string $address
	 * @return bool success
	 */
	public static function sendToUDP( $line, $address = '', $prefix = '' ) {
		global $wgRC2UDPAddress, $wgRC2UDPPrefix, $wgRC2UDPPort;
		# Assume default for standard RC case
		$address = $address ? $address : $wgRC2UDPAddress;
		$prefix = $prefix ? $prefix : $wgRC2UDPPrefix;
		# Notify external application via UDP
		if( $address ) {
			$conn = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			if( $conn ) {
				$line = $prefix . $line;
				socket_sendto( $conn, $line, strlen($line), 0, $address, $wgRC2UDPPort );
				socket_close( $conn );
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Remove newlines and carriage returns
	 * @param string $line
	 * @return string
	 */
	public static function cleanupForIRC( $text ) {
		return str_replace(array("\n", "\r"), array("", ""), $text);
	}
}