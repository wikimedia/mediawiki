<?php
/**
 * Write a messages array as a PHP text.
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup MaintenanceLanguage
 */

/**
 * @ingroup MaintenanceLanguage
 */
class MessageWriter {
	static $optionalComment = 'only translate this message to other languages if you have to change it';
	static $ignoredComment = "do not translate or duplicate this message to other languages";

	static $messageStructure;
	static $blockComments;
	static $ignoredMessages;
	static $optionalMessages;

	/**
	 * Write a messages array as a PHP text and write it to the messages file.
	 *
	 * @param $messages Array: the messages array.
	 * @param $code String: the language code.
	 * @param $write Boolean: write to the messages file?
	 * @param $listUnknown Boolean: list the unknown messages?
	 * @param $removeUnknown Boolean: whether to remove unkown messages
	 */
	public static function writeMessagesToFile( $messages, $code, $write, $listUnknown, $removeUnknown ) {
		# Rewrite the messages array
		$messages = self::writeMessagesArray( $messages, $code == 'en', false, $removeUnknown );
		$messagesText = $messages[0];
		$sortedMessages = $messages[1];

		# Write to the file
		$filename = Language::getMessagesFileName( $code );
		$contents = file_get_contents( $filename );
		if( strpos( $contents, '$messages' ) !== false ) {
			$contents = explode( '$messages', $contents );
			if( $messagesText == '$messages' . $contents[1] ) {
				echo "Generated messages for language $code. Same as the current file.\n";
			} else {
				if( $write ) {
					$new = $contents[0];
					$new .= $messagesText;
					file_put_contents( $filename, $new );
					echo "Generated and wrote messages for language $code.\n";
				} else {
					echo "Generated messages for language $code. Please run the script again (without the parameter \"dry-run\") to write the array to the file.\n";
				}
			}
			if( $listUnknown && isset( $sortedMessages['unknown'] ) && !empty( $sortedMessages['unknown'] ) ) {
				if ( $removeUnknown )
					echo "\nThe following " . count( $sortedMessages['unknown'] ) . " unknown messages have been removed:\n";
				else
					echo "\nThere are " . count( $sortedMessages['unknown'] ) . " unknown messages, please check them:\n";
				foreach( $sortedMessages['unknown'] as $key => $value ) {
					echo "* " . $key . "\n";
				}
			}
		} else {
			echo "Generated messages for language $code. There seem to be no messages array in the file.\n";
		}
	}

	/**
	 * Write a messages array as a PHP text.
	 *
	 * @param $messages Array: the messages array.
	 * @param $ignoredComments Boolean: show comments about ignored and optional
	 *                         messages? (For English.)
	 * @param $prefix String: base path for messages.inc and messageTypes.inc files
	 *                or false for default path (this directory)
	 * @param $removeUnknown Boolean: whether to remove unkown messages
	 *
	 * @return Array of the PHP text and the sorted messages array.
	 */
	public static function writeMessagesArray( $messages, $ignoredComments = false, $prefix = false, $removeUnknown = false ) {
		# Load messages
		$dir = $prefix ? $prefix : dirname( __FILE__ );

		require( $dir . '/messages.inc' );
		self::$messageStructure = $wgMessageStructure;
		self::$blockComments = $wgBlockComments;

		require( $dir . '/messageTypes.inc' );
		self::$ignoredMessages = $wgIgnoredMessages;
		self::$optionalMessages = $wgOptionalMessages;

		# Sort messages to blocks
		$sortedMessages['unknown'] = $messages;
		foreach( self::$messageStructure as $blockName => $block ) {
			foreach( $block as $key ) {
				if( array_key_exists( $key, $sortedMessages['unknown'] ) ) {
					$sortedMessages[$blockName][$key] = $sortedMessages['unknown'][$key];
					unset( $sortedMessages['unknown'][$key] );
				}
			}
		}

		# Write all the messages
		$messagesText = "\$messages = array(
";
		foreach( $sortedMessages as $block => $messages ) {
			# Skip if it's the block of unknown messages - handle that in the end of file
			if( $block == 'unknown' ) {
				continue;
			}

			if( $ignoredComments ) {
				$ignored = self::$ignoredMessages;
				$optional = self::$optionalMessages;
			} else {
				$ignored = array();
				$optional = array();
			}
			$comments = self::makeComments( array_keys( $messages ), $ignored, $optional );

			# Write the block
			$messagesText .= self::writeMessagesBlock( self::$blockComments[$block], $messages, $comments );
		}

		# Write the unknown messages, alphabetically sorted.
		# Of course, we don't have any comments for them, because they are unknown.
		if ( !$removeUnknown ) {
			ksort( $sortedMessages['unknown'] );
			$messagesText .= self::writeMessagesBlock( 'Unknown messages', $sortedMessages['unknown'] );
		}
		$messagesText .= ");
";
		return array( $messagesText, $sortedMessages );
	}

	/**
	 * Generates an array of comments for messages.
	 *
	 * @param $messages Array: key of messages.
	 * @param $ignored Array: list of ingored message keys.
	 * @param $optional Array: list of optional message keys.
	 */
	public static function makeComments( $messages, $ignored, $optional ) {
		# Comment collector
		$commentArray = array();

		# List of keys only
		foreach( $messages as $key ) {
			if( in_array( $key, $ignored ) ) {
				$commentArray[$key] = ' # ' . self::$ignoredComment;
			} elseif( in_array( $key, $optional ) ) {
				$commentArray[$key] = ' # ' . self::$optionalComment;
			}
		}

		return $commentArray;
	}

	/**
	 * Write a block of messages to PHP.
	 *
	 * @param $blockComment String: the comment of whole block.
	 * @param $messages Array: the block messages.
	 * @param $messageComments Array: optional comments for messages in this block.
	 * @param $prefix String: prefix for every line, for indenting purposes.
	 *
	 * @return The block, formatted in PHP.
	 */
	public static function writeMessagesBlock( $blockComment, $messages,
		$messageComments = array(), $prefix = '' ) {

		$blockText = '';

		# Skip the block if it includes no messages
		if( empty( $messages ) ) {
			return '';
		}

		# Format the block comment (if exists); check for multiple lines comments
		if( !empty( $blockComment ) ) {
			if( strpos( $blockComment, "\n" ) === false ) {
				$blockText .= "$prefix# $blockComment
";
			} else {
				$blockText .= "$prefix/*
$blockComment
*/
";
			}
		}

		# Get max key length
		$maxKeyLength = max( array_map( 'strlen', array_keys( $messages ) ) );

		# Format the messages
		foreach( $messages as $key => $value ) {
			# Add the key name
			$blockText .= "$prefix'$key'";

			# Add the appropriate block whitespace
			$blockText .= str_repeat( ' ', $maxKeyLength - strlen( $key ) );

			# Refer to the value
			$blockText .= ' => ';

			# Check for the appropriate apostrophe and add the value
			# Quote \ here, because it needs always escaping
			$value = addcslashes( $value, '\\' );

			# For readability
			$single = "'";
			$double = '"';

			if( strpos( $value, $single ) === false ) {
				# Nothing ugly, just use '
				$blockText .= $single.$value.$single;
			} elseif( strpos( $value, $double ) === false && !preg_match('/\$[a-zA-Z_\x7f-\xff]/', $value) ) {
				# No "-quotes, no variables that need quoting, use "
				$blockText .= $double.$value.$double;
			} else {
				# Something needs quoting, pick the quote which causes less quoting
				$quote = substr_count( $value, $double ) + substr_count( $value, '$' ) >= substr_count( $value, $single ) ? $single : $double;
				if( $quote === $double ) {
					$extra = '$';
				} else {
					$extra = '';
				}
				$blockText .= $quote . addcslashes( $value, $quote . $extra ) . $quote;
			}

			# Comma
			$blockText .= ',';

			# Add comments, if there is any
			if( array_key_exists( $key, $messageComments ) ) {
				$blockText .= $messageComments[$key];
			}

			# Newline
			$blockText .= "
";
		}

		# Newline to end the block
		$blockText .= "
";

		return $blockText;
	}
}
