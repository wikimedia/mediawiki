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
 * @package MediaWiki
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
	function getMessage() {
		return $this->mMessage;
	}
	
	/**
	 * In following PEAR_Error model this could be formatted differently,
	 * but so far it's not.
	 * @return string
	 */
	function toString() {
		return $this->getMessage();
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
 * @package MediaWiki
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
 * @package MediaWiki
 * @todo document
 */
class WikiXmlError extends WikiError {
	/**
	 * @param resource $parser
	 * @param string $message
	 */
	function WikiXmlError( $parser, $message = 'XML parsing error', $context = null, $offset = 0 ) {
		$this->mXmlError = xml_get_error_code( $parser );
		$this->mColumn = xml_get_current_column_number( $parser );
		$this->mLine = xml_get_current_line_number( $parser );
		$this->mByte = xml_get_current_byte_index( $parser );
		$this->mContext = $this->_extractContext( $context, $offset );
		$this->mMessage = $message;
		xml_parser_free( $parser );
		wfDebug( "WikiXmlError: " . $this->getMessage() . "\n" );
	}

	/** @return string */	
	function getMessage() {
		return sprintf( '%s at line %d, col %d (byte %d%s): %s',
			$this->mMessage,
			$this->mLine,
			$this->mColumn,
			$this->mByte,
			$this->mContext,
			xml_error_string( $this->mXmlError ) );
	}
	
	function _extractContext( $context, $offset ) {
		if( is_null( $context ) ) {
			return null;
		} else {
			// Hopefully integer overflow will be handled transparently here
			$inlineOffset = $this->mByte - $offset;
			return '; "' . substr( $context, $inlineOffset, 16 ) . '"';
		}
	}
}

?>
