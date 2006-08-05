<?php
/**
 * Check a language file.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once( 'commandLine.inc' );
require_once( 'languages.inc' );

/**
 * Check a language.
 *
 * @param $code The language code.
 */
function checkLanguage( $code ) {
	global $wgLanguages, $wgHideMessages;

	# Get messages number
	$englishMessagesNumber = count( $wgLanguages->getMessagesFor( 'en' ) );
	$localMessagesNumber = count( $wgLanguages->getMessagesFor( $code ) );

	# Untranslated messages
	$untranslatedMessages = $wgLanguages->getUntranslatedMessages( $code );
	$untranslatedMessagesNumber = count( $untranslatedMessages );
	$wgLanguages->outputMessagesList( $untranslatedMessages, "\n$untranslatedMessagesNumber messages of $englishMessagesNumber are not translated to $code, but exist in en:", $wgHideMessages );

	# Duplicate messages
	$duplicateMessages = $wgLanguages->getDuplicateMessages( $code );
	$duplicateMessagesNumber = count( $duplicateMessages );
	$wgLanguages->outputMessagesList( $duplicateMessages, "\n$duplicateMessagesNumber messages of $localMessagesNumber are translated the same in en and $code:", $wgHideMessages );

	# Obsolete messages
	$obsoleteMessages = $wgLanguages->getObsoleteMessages( $code );
	$obsoleteMessagesNumber = count( $obsoleteMessages );
	$wgLanguages->outputMessagesList( $obsoleteMessages, "\n$obsoleteMessagesNumber messages of $localMessagesNumber are not exist in en, but still exist in $code:", $wgHideMessages );

	# Messages without variables
	$messagesWithoutVariables = $wgLanguages->getMessagesWithoutVariables( $code );
	$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
	$wgLanguages->outputMessagesList( $messagesWithoutVariables, "\n$messagesWithoutVariablesNumber messages of $localMessagesNumber in $code don't use some variables while en uses them:", $wgHideMessages );

	# Empty messages
	$emptyMessages = $wgLanguages->getEmptyMessages( $code );
	$emptyMessagesNumber = count( $emptyMessages );
	$wgLanguages->outputMessagesList( $emptyMessages, "\n$emptyMessagesNumber messages of $localMessagesNumber in $code are empty or -:", $wgHideMessages );

	# Messages with whitespace
	$messagesWithWhitespace = $wgLanguages->getMessagesWithWhitespace( $code );
	$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
	$wgLanguages->outputMessagesList( $messagesWithWhitespace, "\n$messagesWithWhitespaceNumber messages of $localMessagesNumber in $code have a trailing whitespace:", $wgHideMessages );

	# Non-XHTML messages
	$nonXHTMLMessages = $wgLanguages->getNonXHTMLMessages( $code );
	$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
	$wgLanguages->outputMessagesList( $nonXHTMLMessages, "\n$nonXHTMLMessagesNumber messages of $localMessagesNumber in $code are not well-formed XHTML:", $wgHideMessages );

	# Non-XHTML messages
	$messagesWithWrongChars = $wgLanguages->getMessagesWithWrongChars( $code );
	$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
	$wgLanguages->outputMessagesList( $messagesWithWrongChars, "\n$messagesWithWrongCharsNumber messages of $localMessagesNumber in $code include hidden chars which should not be used in the messages:", $wgHideMessages );
}

# Show help
if ( isset( $options['help'] ) ) {
	echo "Run this script to check a specific language file.\n";
	echo "Parameters:\n";
	echo "\tLanguage code, the installation default language will be used if not specified; can also specify \"all\" to check all the languages.\n";
	echo "Options:\n";
	echo "\thelp: Show help.\n";
	echo "\thide: Only show the numbers of messages with the problem, hide the messages themselves.\n";
	exit();
}
$wgHideMessages = isset( $options['hide'] );

# Get the language code
if ( isset( $args[0] ) ) {
	$code = $args[0];
} else {
	$code = $wgLang->getCode();
}

# Can't check English
if ( $code == 'en' ) {
	echo "Current selected language is English, which cannot be checked.\n";
	exit();
}

# Get language objects
$wgLanguages = new languages();

# Check the language
if ( $code == 'all' ) {
	foreach ( $wgLanguages->getList() as $language ) {
		checkLanguage( $language );
	}
} else {
	checkLanguage( $code );
}

?>
