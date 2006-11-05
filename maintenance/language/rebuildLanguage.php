<?php
/**
 * Rewrite the messages array in the files languages/messages/MessagesXX.php.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'writeMessagesArray.inc' );

/**
 * Rewrite a messages array.
 *
 * @param $code The language code.
 * @param $write Write to the messages file?
 */
function rebuildLanguage( $code, $write ) {
	global $wgLanguages, $wg;

	# Get messages
	$messages = $wgLanguages->getMessages( $code );
	$messages = $messages['all'];

	# Rewrite messages array
	$messagesText = writeMessagesArray( $messages );

	# Write to the file
	if ( $write ) {
		$filename = Language::getMessagesFileName( $code );
		$contents = file_get_contents( $filename );
		if ( strpos( $contents, '$messages' ) !== false ) {
			$new = explode( '$messages', $contents );
			$new = $new[0];
			$new .= $messagesText;
			$new .= "\n?>\n";
			file_put_contents( $filename, $new );
			echo "Generated and wrote messages in language $code.\n";
		}
	} else {
		echo "Generated messages in language $code.\n";
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

END;
	exit();
}

# Get the language code
if ( isset( $options['lang'] ) ) {
	$wgCode = $options['lang'];
} else {
	$wgCode = $wgContLang->getCode();
}

# Get the write options
$wgWriteToFile = !isset( $options['dry-run'] );

# Get language objects
$wgLanguages = new languages();

# Write all the language
if ( $wgCode == 'all' ) {
	foreach ( $wgLanguages->getLanguages() as $language ) {
		rebuildLanguage( $language, $wgWriteToFile );
	}
} else {
	rebuildLanguage( $wgCode, $wgWriteToFile );
}

?>
