<?php
/**
 * MediaWiki error classes
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 */

/**
 * Since PHP4 doesn't have exceptions, here's some error objects
 * loosely modeled on the standard PEAR_Error model...
 */
class WikiError {
	/**
	 * @param string $message
	 */
	function WikiError( $message ) {
		$this->mMessage = $message;
	}
	
	/**
	 * @return string Plaintext error message to display
	 */
	function toString() {
		return $this->mMessage;
	}
	
	/**
	 * Returns true if the given object is a WikiError-descended
	 * error object, false otherwise.
	 *
	 * @param mixed $object
	 * @return bool
	 * @static
	 */
	function isError( &$object ) {
		return is_a( $object, 'WikiError' );
	}
}

/**
 * Localized error message object
 */
class WikiErrorMsg extends WikiError {
	/**
	 * @param string $message Wiki message name
	 * @param ... parameters to pass to wfMsg()
	 */
	function WikiErrorMsg( $message/*, ... */ ) {
		$args = func_get_args();
		array_shift( $args );
		$this->mMessage = wfMsgReal( $message, $args, true );
	}
}

/**
 * 
 */
class WikiXmlError extends WikiError {
	/**
	 * @param resource $parser
	 * @param string $message
	 */
	function WikiXmlError( $parser, $message = '' ) {
		$this->mXmlError = xml_get_error_code( $parser );
		$this->mMessage = $message;
		xml_parser_free( $parser );
	}
	
	function toString() {
		return $this->mMessage . ': ' . xml_error_string( $this->mXmlError );
	}
}

?>