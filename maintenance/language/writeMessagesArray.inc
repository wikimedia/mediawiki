<?php
/**
 * Write a messages array as a PHP text.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once( 'messages.inc' );

/**
 * Write a messages array as a PHP text.
 *
 * @param $messages The messages array.
 *
 * @return The PHP text.
 */
function writeMessagesArray( $messages ) {
	global $wgMessageStrucutre, $wgBlockComments, $wgMessageComments;

	# Sort messages to blocks
	$sortedMessages['unknown'] = $messages;
	foreach ( $messages as $key => $value ) {
		foreach ( $wgMessageStrucutre as $blockName => $block ) {
			if ( in_array( $key, $block ) ) {
				$sortedMessages[$blockName][$key] = $value;
				unset( $sortedMessages['unknown'][$key] );
				break;
			}
		}
	}

	# Write all the messages
	$messagesText = "\$messages = array(\n";
	foreach( $sortedMessages as $block => $messages ) {
		# Skip if it's the block of unknown messages - handle that in the end of file
		if ( $block == 'unknown' ) {
			continue;
		}

		# Write the block
		$messagesText .= writeMessagesBlock( $block, $wgBlockComments[$block], $messages );
	}
	$messagesText .= writeMessagesBlock( 'unknown', 'Unknown messages', $sortedMessages['unknown'] );
	$messagesText .= ");\n";

	return $messagesText;
}

/**
 * Write a block of messages to PHP.
 *
 * @param $name The block name.
 * @param $comment The block comment.
 * @param $messages The block messages.
 *
 * @return The block, formatted in PHP.
 */
function writeMessagesBlock( $name, $comment, $messages ) {
	global $wgMessageComments;
	$blockText = '';

	# Skip the block if it includes no messages
	if ( empty( $messages ) ) {
		return '';
	}

	# Format the block comment (if exists); check for multiple lines comments
	if ( !empty( $comment ) ) {
		if ( strpos( $comment, "\n" ) === false ) {
			$blockText .= "# $comment\n";
		} else {
			$blockText .= "/*\n$comment\n*/\n";
		}
	}

	# Get max key length
	$maxKeyLength = 0;
	foreach( array_keys( $messages ) as $key ) {
		if ( strlen( $key ) > $maxKeyLength ) {
			$maxKeyLength = strlen( $key );
		}
	}

	# Format the messages
	foreach( $messages as $key => $value ) {
		# Add the key name
		$blockText .= "'$key'";

		# Add the appropriate block whitespace
		for ( $i = 1; $i <= ( $maxKeyLength - strlen( $key ) ); $i++ ) {
			$blockText .= ' ';
		}

		# Refer to the value
		$blockText .= ' => ';

		# Check for the appropriate apostrophe and add the value
		if ( strpos( $value, "'" ) === false ) {
			$blockText .= "'$value'";
		} elseif ( strpos( $value, '"' ) === false ) {
			$blockText .= "\"$value\"";
		} else {
			$blockText .= "'" . str_replace( "'", "\'", $value ) . "'";
		}

		# Comma
		$blockText .= ',';

		if ( array_key_exists( $key, $wgMessageComments ) ) {
			$blockText .= ' # ' . $wgMessageComments[$key];
		}

		# Newline
		$blockText .= "\n";
	}

	# Newline to end the block
	$blockText .= "\n";

	return $blockText;
}

?>
