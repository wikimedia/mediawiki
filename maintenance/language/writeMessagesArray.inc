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
	foreach( $sortedMessages as $blockName => $block ) {
		# Continue if it's the block of unknown messages, or the block is empty
		if ( $blockName == 'unknown' || empty( $block ) ) {
			continue;
		}

		# Get the block comment and format it (if exist); check for multiple lines comments
		$comment = $wgBlockComments[$blockName];
		if ( !empty( $comment ) ) {
			if ( strpos( $comment, "\n" ) === false ) {
				$messagesText .= "# $comment\n";
			} else {
				$messagesText .= "/*\n$comment\n*/\n";
			}
		}

		# Get max key length
		$maxKeyLength = 0;
		foreach( array_keys( $block ) as $key ) {
			if ( strlen( $key ) > $maxKeyLength ) {
				$maxKeyLength = strlen( $key );
			}
		}

		# Format the messages
		foreach( $block as $key => $value ) {
			# Add the key name
			$messagesText .= "'$key'";

			# Add the appropriate block whitespace
			for ( $i = 1; $i <= ( $maxKeyLength - strlen( $key ) ); $i++ ) {
				$messagesText .= ' ';
			}

			# Refer to the value
			$messagesText .= ' => ';

			# Check for the appropriate apostrophe and add the value
			if ( strpos( $value, "'" ) === false ) {
				$messagesText .= "'$value'";
			} elseif ( strpos( $value, '"' ) === false ) {
				$messagesText .= "\"$value\"";
			} else {
				$messagesText .= "'" . str_replace( "'", "\'", $value ) . "'";
			}

			# Comma
			$messagesText .= ',';

			if ( array_key_exists( $key, $wgMessageComments ) ) {
				$messagesText .= ' # ' . $wgMessageComments[$key];
			}

			# Newline
			$messagesText .= "\n";
		}

		# Newline to end the block
		$messagesText .= "\n";
	}
	$messagesText .= ");\n";

	return $messagesText;
}

?>
