<?php
/**
 * Check a language.
 *
 * @todo Stop with globals.
 * @param $languages The languages object.
 * @param $code The language code.
 * @return Number of errors found.
 */
function checkLanguage( $languages, $code ) {
	global $wgRequiredMessagesNumber, $wgDisplayLevel, $wgLinks, $wgWikiLanguage, $wgChecks;

	# Get messages
	$messages = $languages->getMessages( $code );
	$messagesNumber = count( $messages['translated'] );

	# Skip the checks if specified
	if ( $wgDisplayLevel == 0 ) {
		return;
	}

	// Initialize counts
	$untranslatedMessagesNumber = $duplicateMessagesNumber = $obsoleteMessagesNumber
	= $messagesWithoutVariablesNumber = $messagesWithoutPluralNumber = $emptyMessagesNumber
	= $messagesWithWhitespaceNumber = $nonXHTMLMessagesNumber = $messagesWithWrongCharsNumber
	= 0;

	# Untranslated messages
	if ( in_array( 'untranslated', $wgChecks ) ) {
		$untranslatedMessages = $languages->getUntranslatedMessages( $code );
		$untranslatedMessagesNumber = count( $untranslatedMessages );
		$languages->outputMessagesList( $untranslatedMessages, $code, "\n$untranslatedMessagesNumber messages of $wgRequiredMessagesNumber are not translated to $code, but exist in en:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Duplicate messages
	if ( in_array( 'duplicate', $wgChecks ) ) {
		$duplicateMessages = $languages->getDuplicateMessages( $code );
		$duplicateMessagesNumber = count( $duplicateMessages );
		$languages->outputMessagesList( $duplicateMessages, $code, "\n$duplicateMessagesNumber messages of $messagesNumber are translated the same in en and $code:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Obsolete messages
	if ( in_array( 'obsolete', $wgChecks ) ) {
		$obsoleteMessages = $messages['obsolete'];
		$obsoleteMessagesNumber = count( $obsoleteMessages );
		$languages->outputMessagesList( $obsoleteMessages, $code, "\n$obsoleteMessagesNumber messages of $messagesNumber do not exist in en (or are in the ignored list), but still exist in $code:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages without variables
	if ( in_array( 'variables', $wgChecks ) ) {
		$messagesWithoutVariables = $languages->getMessagesWithoutVariables( $code );
		$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
		$languages->outputMessagesList( $messagesWithoutVariables, $code, "\n$messagesWithoutVariablesNumber messages of $messagesNumber in $code don't use some variables while en uses them:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages without plural
	if ( in_array( 'plural', $wgChecks ) ) {
		$messagesWithoutPlural = $languages->getMessagesWithoutPlural( $code );
		$messagesWithoutPluralNumber = count( $messagesWithoutPlural );
		$languages->outputMessagesList( $messagesWithoutPlural, $code, "\n$messagesWithoutPluralNumber messages of $messagesNumber in $code don't use {{plural}} while en uses it:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Empty messages
	if ( in_array( 'empty', $wgChecks ) ) {
		$emptyMessages = $languages->getEmptyMessages( $code );
		$emptyMessagesNumber = count( $emptyMessages );
		$languages->outputMessagesList( $emptyMessages, $code, "\n$emptyMessagesNumber messages of $messagesNumber in $code are empty or -:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages with whitespace
	if ( in_array( 'whitespace', $wgChecks ) ) {
		$messagesWithWhitespace = $languages->getMessagesWithWhitespace( $code );
		$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
		$languages->outputMessagesList( $messagesWithWhitespace, $code, "\n$messagesWithWhitespaceNumber messages of $messagesNumber in $code have a trailing whitespace:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Non-XHTML messages
	if ( in_array( 'xhtml', $wgChecks ) ) {
		$nonXHTMLMessages = $languages->getNonXHTMLMessages( $code );
		$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
		$languages->outputMessagesList( $nonXHTMLMessages, $code, "\n$nonXHTMLMessagesNumber messages of $messagesNumber in $code are not well-formed XHTML:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages with wrong characters
	if ( in_array( 'chars', $wgChecks ) ) {
		$messagesWithWrongChars = $languages->getMessagesWithWrongChars( $code );
		$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
		$languages->outputMessagesList( $messagesWithWrongChars, $code, "\n$messagesWithWrongCharsNumber messages of $messagesNumber in $code include hidden chars which should not be used in the messages:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	return ($untranslatedMessagesNumber + $duplicateMessagesNumber + $obsoleteMessagesNumber + $messagesWithoutVariablesNumber + $messagesWithoutPluralNumber + $emptyMessagesNumber + $messagesWithWhitespaceNumber + $nonXHTMLMessagesNumber + $messagesWithWrongCharsNumber);
}
?>
