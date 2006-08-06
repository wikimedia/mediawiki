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
	global $wgLanguages, $wgChecks, $wgHideMessages, $wgHideMessagesValues;

	# Get messages number
	$translatableMessagesNumber = count( $wgLanguages->getTranslatableMessages() );
	$localMessagesNumber = count( $wgLanguages->getMessagesFor( $code ) );

	# Untranslated messages
	if ( in_array( 'untranslated', $wgChecks ) ) {
		$untranslatedMessages = $wgLanguages->getUntranslatedMessages( $code );
		$untranslatedMessagesNumber = count( $untranslatedMessages );
		$wgLanguages->outputMessagesList( $untranslatedMessages, "\n$untranslatedMessagesNumber messages of $translatableMessagesNumber are not translated to $code, but exist in en:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Duplicate messages
	if ( in_array( 'duplicate', $wgChecks ) ) {
		$duplicateMessages = $wgLanguages->getDuplicateMessages( $code );
		$duplicateMessagesNumber = count( $duplicateMessages );
		$wgLanguages->outputMessagesList( $duplicateMessages, "\n$duplicateMessagesNumber messages of $localMessagesNumber are translated the same in en and $code:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Obsolete messages
	if ( in_array( 'obsolete', $wgChecks ) ) {
		$obsoleteMessages = $wgLanguages->getObsoleteMessages( $code );
		$obsoleteMessagesNumber = count( $obsoleteMessages );
		$wgLanguages->outputMessagesList( $obsoleteMessages, "\n$obsoleteMessagesNumber messages of $localMessagesNumber are not exist in en (or are in the ignored list), but still exist in $code:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Messages without variables
	if ( in_array( 'variables', $wgChecks ) ) {
		$messagesWithoutVariables = $wgLanguages->getMessagesWithoutVariables( $code );
		$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
		$wgLanguages->outputMessagesList( $messagesWithoutVariables, "\n$messagesWithoutVariablesNumber messages of $localMessagesNumber in $code don't use some variables while en uses them:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Empty messages
	if ( in_array( 'empty', $wgChecks ) ) {
		$emptyMessages = $wgLanguages->getEmptyMessages( $code );
		$emptyMessagesNumber = count( $emptyMessages );
		$wgLanguages->outputMessagesList( $emptyMessages, "\n$emptyMessagesNumber messages of $localMessagesNumber in $code are empty or -:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Messages with whitespace
	if ( in_array( 'whitespace', $wgChecks ) ) {
		$messagesWithWhitespace = $wgLanguages->getMessagesWithWhitespace( $code );
		$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
		$wgLanguages->outputMessagesList( $messagesWithWhitespace, "\n$messagesWithWhitespaceNumber messages of $localMessagesNumber in $code have a trailing whitespace:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Non-XHTML messages
	if ( in_array( 'xhtml', $wgChecks ) ) {
		$nonXHTMLMessages = $wgLanguages->getNonXHTMLMessages( $code );
		$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
		$wgLanguages->outputMessagesList( $nonXHTMLMessages, "\n$nonXHTMLMessagesNumber messages of $localMessagesNumber in $code are not well-formed XHTML:", $wgHideMessages, $wgHideMessagesValues );
	}

	# Messages with wrong characters
	if ( in_array( 'chars', $wgChecks ) ) {
		$messagesWithWrongChars = $wgLanguages->getMessagesWithWrongChars( $code );
		$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
		$wgLanguages->outputMessagesList( $messagesWithWrongChars, "\n$messagesWithWrongCharsNumber messages of $localMessagesNumber in $code include hidden chars which should not be used in the messages:", $wgHideMessages, $wgHideMessagesValues );
	}
}

# Show help
if ( isset( $options['help'] ) ) {
	echo "Run this script to check a specific language file.\n";
	echo "Parameters:\n";
	echo "\t* lang: Language code, the installation default language will be used if not specified; can also specify \"all\" to check all the languages.\n";
	echo "\t* help: Show help.\n";
	echo "\t* hide: Only show the numbers of messages with the problem, hide the messages themselves.\n";
	echo "\t* hidev: Only show the keys of messages with the problem, hide the values of messages.\n";
	echo "\t* whitelist: Make only the following checks (form: code,code).\n";
	echo "\t* blacklist: Don't make the following checks (form: code,code).\n";
	echo "Check codes (ideally, should be zero):\n";
	echo "\t* untranslated: Messages which are translatable, but not translated.";
	echo "\t* duplicate: Messages which are translated the same to English.";
	echo "\t* obsolete: Messages which are untranslatable, but translated.";
	echo "\t* variables: Messages without variables which should be used.";
	echo "\t* empty: Empty messages.";
	echo "\t* whitespace: Messages which have trailing whitespace.";
	echo "\t* xhtml: Messages which are not well-formed XHTML.";
	echo "\t* chars: Messages with hidden characters.";
	exit();
}

# Get the language code
if ( isset( $options['lang'] ) ) {
	$wgCode = $options['lang'];
} else {
	$wgCode = $wgLang->getCode();
}

# Can't check English
if ( $wgCode == 'en' ) {
	echo "Current selected language is English, which cannot be checked.\n";
	exit();
}

# Get the options of hiding things
$wgHideMessages = isset( $options['hide'] );
$wgHideMessagesValues = isset( $options['hidev'] );

# Get the checks to do
$wgChecks = array( 'untranslated', 'duplicate', 'obsolete', 'variables', 'empty', 'whitespace', 'xhtml', 'chars' );
if ( isset( $options['whitelist'] ) ) {
	$wgChecks = explode( ',', $options['whitelist'] );
} elseif ( isset( $options['blacklist'] ) ) {
	$wgChecks = array_diff( $wgChecks, explode( ',', $options['blacklist'] ) );
}

# Get language objects
$wgLanguages = new languages();

# Check the language
if ( $wgCode == 'all' ) {
	foreach ( $wgLanguages->getList() as $language ) {
		if ( $language != 'en' && $language != 'enRTL' ) {
			checkLanguage( $language );
		}
	}
} else {
	checkLanguage( $wgCode );
}

?>
