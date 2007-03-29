<?php
/**
 * Rewrite the messages array in the files languages/messages/MessagesXX.php.
 *
 * @addtogroup Maintenance
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'writeMessagesArray.inc' );

/**
 * Rewrite a messages array.
 *
 * @param $code The language code.
 * @param $write Write to the messages file?
 * @param $listUnknown List the unknown messages?
 */
function rebuildLanguage( $code, $write, $listUnknown ) {
	global $wgLanguages;

	# Get messages
	$messages = $wgLanguages->getMessages( $code );
	$messages = $messages['all'];

	# Rewrite messages array
	$messages = writeMessagesArray( $messages, $code == 'en' );
	$messagesText = $messages[0];
	$sortedMessages = $messages[1];

	# Write to the file
	$filename = Language::getMessagesFileName( $code );
	$contents = file_get_contents( $filename );
	if ( strpos( $contents, '$messages' ) !== false ) {
		$contents = explode( '$messages', $contents );
		if ( $messagesText . "\n?>\n" == '$messages' . $contents[1] ) {
			echo "Generated messages for language $code. Same to the current file.\n";
		} else {
			if ( $write ) {
				$new = $contents[0];
				$new .= $messagesText;
				$new .= "\n?>\n";
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

# Show help
if ( isset( $options['help'] ) ) {
	echo <<<END
Run this script to rewrite the messages array in the files languages/messages/MessagesXX.php.
Parameters:
	* lang: Language code (default: the installation default language). You can also specify "all" to check all the languages.
	* help: Show this help.
Options:
	* dry-run: Don't write the array to the file.
	* no-unknown: Don't list the unknown messages.

END;
	exit();
}

# Get the language code
if ( isset( $options['lang'] ) ) {
	$wgCode = $options['lang'];
} else {
	$wgCode = $wgContLang->getCode();
}

# Get the options
$wgWriteToFile = !isset( $options['dry-run'] );
$wgListUnknownMessages = !isset( $options['no-unknown'] );

# Get language objects
$wgLanguages = new languages();

# Write all the language
if ( $wgCode == 'all' ) {
	foreach ( $wgLanguages->getLanguages() as $language ) {
		rebuildLanguage( $language, $wgWriteToFile, $wgListUnknownMessages );
	}
} else {
	rebuildLanguage( $wgCode, $wgWriteToFile, $wgListUnknownMessages );
}

?>
