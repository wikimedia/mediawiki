<?php
/**
 * Write a messages array as a PHP text.
 *
 * @addtogroup Maintenance
 */

class MessageWriter {

/**
 * Write a messages array as a PHP text and write it to the messages file.
 *
 * @param $messages The messages array.
 * @param $code The language code.
 * @param $write Write to the messages file?
 * @param $listUnknown List the unknown messages?
 */
public static function writeMessagesToFile( $messages, $code, $write, $listUnknown ) {
	# Rewrite the messages array
	$messages = self::writeMessagesArray( $messages, $code == 'en' );
	$messagesText = $messages[0];
	$sortedMessages = $messages[1];

	# Write to the file
	$filename = Language::getMessagesFileName( $code );
	$contents = file_get_contents( $filename );
	if ( strpos( $contents, '$messages' ) !== false ) {
		$contents = explode( '$messages', $contents );
		if ( $messagesText == '$messages' . $contents[1] ) {
			echo "Generated messages for language $code. Same as the current file.\n";
		} else {
			if ( $write ) {
				$new = $contents[0];
				$new .= $messagesText;
				file_put_contents( $filename, $new );
				echo "Generated and wrote messages for language $code.\n";
			} else {
				echo "Generated messages for language $code. Please run the script again (without the parameter \"dry-run\") to write the array to the file.\n";
			}
		}
		if ( $listUnknown && isset( $sortedMessages['unknown'] ) && !empty( $sortedMessages['unknown'] ) ) {
			echo "\nThere are " . count( $sortedMessages['unknown'] ) . " unknown messages, please check them:\n";
			foreach ( $sortedMessages['unknown'] as $key => $value ) {
				echo "* " . $key . "\n";
			}
		}
	} else {
		echo "Generated messages for language $code. There seems to be no messages array in the file.\n";
	}
}

/**
 * Write a messages array as a PHP text.
 *
 * @param $messages The messages array.
 * @param $ignoredComments Show comments about ignored and optional messages? (For English.)
 *
 * @return Array of the PHP text and the sorted messages array.
 */
public static function writeMessagesArray( $messages, $ignoredComments = false ) {
	# $wgMessageStructure, $wgBlockComments, $wgMessageComments;
	require( dirname( __FILE__ ) . '/messages.inc' );
	# $wgIgnoredMessages, $wgOptionalMessages;
	require( dirname( __FILE__ ) . '/messageTypes.inc' );

	if (!$ignoredComments) {
		$wgOptionalMessages = $wgIgnoredMessages = array();
	}

	# Sort messages to blocks
	$sortedMessages['unknown'] = $messages;
	foreach ( $wgMessageStructure as $blockName => $block ) {
		foreach ( $block as $key ) {
			if ( array_key_exists( $key, $sortedMessages['unknown'] ) ) {
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
		if ( $block == 'unknown' ) {
			continue;
		}

		$comments = self::makeComments( array_keys($messages), $wgMessageComments,
			$wgIgnoredMessages, $wgOptionalMessages
		);

		# Write the block
		$messagesText .= self::writeMessagesBlock( $wgBlockComments[$block], $messages, $comments );
	}

	// Write the unknown messages, alphabetically sorted.
	// Of course, we don't have any comments for them, because the are unknown.
	ksort( $sortedMessages['unknown'] );
	$messagesText .= self::writeMessagesBlock( 'Unknown messages', $sortedMessages['unknown'] );
	$messagesText .= ");
";

	return array( $messagesText, $sortedMessages );
}

static $optionalComment = 'only translate this message to other languages if you have to change it';
static $ignoredComment = 'don\'t translate or duplicate this message to other languages';

/**
 * Generates an array of comments for messages.
 *
 * @param $messages Key of messages.
 * @param $comments Comments for messages, indexed by key.
 * @param $ignored List of ingored message keys.
 * @param $optional List of optional message keys.
 */
public static function makeComments( $messages, $comments, $ignored, $optional ) {
	// Comment collector
	$commentArray = array();

	// List of keys only
	foreach ( $messages as $key ) {
		$commentsForKey = array();

		// Add descriptive comment for this message if there is one
		if ( array_key_exists( $key, $comments ) ) {
			$commentsForKey[] = $comments[$key];
		}

		// For translator information only
		if ( in_array( $key, $ignored ) ) {
			$commentsForKey[] = self::$ignoredComment;
		} elseif ( in_array( $key, $optional ) ) {
			$commentsForKey[] = self::$optionalComment;
		}

		// Formet them nicely and add to array
		if ( count($commentsForKey) ) {
			$commentArray[$key] = ' # ' . implode( '; ', $commentsForKey );
		}
	}

	return $commentArray;
}

/**
 * Write a block of messages to PHP.
 *
 * @param $blockComment The comment of whole block.
 * @param $messages The block messages.
 * @param $messageComments Optional comments for messages in this block.
 * @param $prefix Prefix for every line, for indenting purposes.
 *
 * @return The block, formatted in PHP.
 */
public static function writeMessagesBlock( $blockComment, $messages,
	$messageComments = array(), $prefix = '' ) {

	$blockText = '';

	# Skip the block if it includes no messages
	if ( empty( $messages ) ) {
		return '';
	}

	# Format the block comment (if exists); check for multiple lines comments
	if ( !empty( $blockComment ) ) {
		if ( strpos( $blockComment, "\n" ) === false ) {
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
		if ( strpos( $value, "'" ) === false ) {
			$blockText .= "'$value'";
		} elseif ( strpos( $value, '"' ) === false && !preg_match('/\$[a-zA-Z]/', $value) ) {
			$blockText .= "\"$value\"";
		} else {
			# Pick the less numerous one to escape
			$quote = substr_count( $value, '"' ) + substr_count( $value, '$' ) >= substr_count( $value, "'" ) ? "'" : '"';
			if ('"' == $quote) { $extra = '$'; }
			else { $extra = ''; }
			$blockText .= $quote . addcslashes( $value, $quote.'\\'.$extra ) . $quote;
		}

		# Comma
		$blockText .= ',';

		# Add comments, if there is any
		if ( array_key_exists( $key, $messageComments ) ) {
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

} // CLASS MessageWriter

?>
