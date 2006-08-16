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
	global $wgLanguages, $wgDisplayLevel, $wgLinks, $wgChecks;

	# Get messages number
	$translatableMessagesNumber = count( $wgLanguages->getTranslatableMessages() );
	$localMessagesNumber = count( $wgLanguages->getMessagesFor( $code ) );

	# Skip the checks if specified
	if ( $wgDisplayLevel == 0 ) {
		return;
	}

	# Untranslated messages
	if ( in_array( 'untranslated', $wgChecks ) ) {
		$untranslatedMessages = $wgLanguages->getUntranslatedMessages( $code );
		$untranslatedMessagesNumber = count( $untranslatedMessages );
		$wgLanguages->outputMessagesList( $untranslatedMessages, $code, "\n$untranslatedMessagesNumber messages of $translatableMessagesNumber are not translated to $code, but exist in en:", $wgDisplayLevel, $wgLinks );
	}

	# Duplicate messages
	if ( in_array( 'duplicate', $wgChecks ) ) {
		$duplicateMessages = $wgLanguages->getDuplicateMessages( $code );
		$duplicateMessagesNumber = count( $duplicateMessages );
		$wgLanguages->outputMessagesList( $duplicateMessages, $code, "\n$duplicateMessagesNumber messages of $localMessagesNumber are translated the same in en and $code:", $wgDisplayLevel, $wgLinks );
	}

	# Obsolete messages
	if ( in_array( 'obsolete', $wgChecks ) ) {
		$obsoleteMessages = $wgLanguages->getObsoleteMessages( $code );
		$obsoleteMessagesNumber = count( $obsoleteMessages );
		$wgLanguages->outputMessagesList( $obsoleteMessages, $code, "\n$obsoleteMessagesNumber messages of $localMessagesNumber are not exist in en (or are in the ignored list), but still exist in $code:", $wgDisplayLevel, $wgLinks );
	}

	# Messages without variables
	if ( in_array( 'variables', $wgChecks ) ) {
		$messagesWithoutVariables = $wgLanguages->getMessagesWithoutVariables( $code );
		$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
		$wgLanguages->outputMessagesList( $messagesWithoutVariables, $code, "\n$messagesWithoutVariablesNumber messages of $localMessagesNumber in $code don't use some variables while en uses them:", $wgDisplayLevel, $wgLinks );
	}

	# Empty messages
	if ( in_array( 'empty', $wgChecks ) ) {
		$emptyMessages = $wgLanguages->getEmptyMessages( $code );
		$emptyMessagesNumber = count( $emptyMessages );
		$wgLanguages->outputMessagesList( $emptyMessages, $code, "\n$emptyMessagesNumber messages of $localMessagesNumber in $code are empty or -:", $wgDisplayLevel, $wgLinks );
	}

	# Messages with whitespace
	if ( in_array( 'whitespace', $wgChecks ) ) {
		$messagesWithWhitespace = $wgLanguages->getMessagesWithWhitespace( $code );
		$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
		$wgLanguages->outputMessagesList( $messagesWithWhitespace, $code, "\n$messagesWithWhitespaceNumber messages of $localMessagesNumber in $code have a trailing whitespace:", $wgDisplayLevel, $wgLinks );
	}

	# Non-XHTML messages
	if ( in_array( 'xhtml', $wgChecks ) ) {
		$nonXHTMLMessages = $wgLanguages->getNonXHTMLMessages( $code );
		$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
		$wgLanguages->outputMessagesList( $nonXHTMLMessages, $code, "\n$nonXHTMLMessagesNumber messages of $localMessagesNumber in $code are not well-formed XHTML:", $wgDisplayLevel, $wgLinks );
	}

	# Messages with wrong characters
	if ( in_array( 'chars', $wgChecks ) ) {
		$messagesWithWrongChars = $wgLanguages->getMessagesWithWrongChars( $code );
		$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
		$wgLanguages->outputMessagesList( $messagesWithWrongChars, $code, "\n$messagesWithWrongCharsNumber messages of $localMessagesNumber in $code include hidden chars which should not be used in the messages:", $wgDisplayLevel, $wgLinks );
	}
}

# Show help
if ( isset( $options['help'] ) ) {
	echo "Run this script to check a specific language file.\n";
	echo "Parameters:\n";
	echo "\t* lang: Language code (default: the installation default language). You can also specify \"all\" to check all the languages.\n";
	echo "\t* help: Show help.\n";
	echo "\t* level: Show the following level (default: 2).\n";
	echo "\t* links: Link the message values (default off).\n";
	echo "\t* whitelist: Make only the following checks (form: code,code).\n";
	echo "\t* blacklist: Don't make the following checks (form: code,code).\n";
	echo "Check codes (ideally, should be zero; all the checks are executed by default):\n";
	echo "\t* untranslated: Messages which are translatable, but not translated.";
	echo "\t* duplicate: Messages which are translated the same to English.";
	echo "\t* obsolete: Messages which are untranslatable, but translated.";
	echo "\t* variables: Messages without variables which should be used.";
	echo "\t* empty: Empty messages.";
	echo "\t* whitespace: Messages which have trailing whitespace.";
	echo "\t* xhtml: Messages which are not well-formed XHTML.";
	echo "\t* chars: Messages with hidden characters.";
	echo "Display levels (default: 2):\n";
	echo "\t* 0: Skip the checks (useful for checking syntax).";
	echo "\t* 1: Show only the stub headers and number of wrong messages, without list of messages.";
	echo "\t* 2: Show only the headers and the message keys, without the message values.";
	echo "\t* 3: Show both the headers and the complete messages, with both keys and values.";
	exit();
}

# Get the language code
if ( isset( $options['lang'] ) ) {
	$wgCode = $options['lang'];
} else {
	$wgCode = $wgContLang->getCode();
}

# Get the display level
if ( isset( $options['level'] ) ) {
	$wgDisplayLevel = $options['level'];
} else {
	$wgDisplayLevel = 2;
}

# Get the links option
$wgLinks = isset( $options['links'] );

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
	# Can't check English
	if ( $wgCode == 'en' ) {
		echo "Current selected language is English, which cannot be checked.\n";
	} else if ( $wgCode == 'enRTL' ) {
		echo "Current selected language is RTL English, which cannot be checked.\n";
	} else {
		checkLanguage( $wgCode );
	}
}

?>
