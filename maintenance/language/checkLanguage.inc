<?php
/**
 * Check a language.
 *
 * @todo Stop with globals.
 * @param $code The language code.
 */
function checkLanguage( $wgLanguages, $code ) {
	global $wgRequiredMessagesNumber, $wgDisplayLevel, $wgLinks, $wgWikiLanguage, $wgChecks;

	# Get messages
	$messages = $wgLanguages->getMessages( $code );
	$messagesNumber = count( $messages['translated'] );

	# Skip the checks if specified
	if ( $wgDisplayLevel == 0 ) {
		return;
	}

	# Untranslated messages
	if ( in_array( 'untranslated', $wgChecks ) ) {
		$untranslatedMessages = $wgLanguages->getUntranslatedMessages( $code );
		$untranslatedMessagesNumber = count( $untranslatedMessages );
		$wgLanguages->outputMessagesList( $untranslatedMessages, $code, "\n$untranslatedMessagesNumber messages of $wgRequiredMessagesNumber are not translated to $code, but exist in en:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Duplicate messages
	if ( in_array( 'duplicate', $wgChecks ) ) {
		$duplicateMessages = $wgLanguages->getDuplicateMessages( $code );
		$duplicateMessagesNumber = count( $duplicateMessages );
		$wgLanguages->outputMessagesList( $duplicateMessages, $code, "\n$duplicateMessagesNumber messages of $messagesNumber are translated the same in en and $code:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Obsolete messages
	if ( in_array( 'obsolete', $wgChecks ) ) {
		$obsoleteMessages = $messages['obsolete'];
		$obsoleteMessagesNumber = count( $obsoleteMessages );
		$wgLanguages->outputMessagesList( $obsoleteMessages, $code, "\n$obsoleteMessagesNumber messages of $messagesNumber are not exist in en (or are in the ignored list), but still exist in $code:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages without variables
	if ( in_array( 'variables', $wgChecks ) ) {
		$messagesWithoutVariables = $wgLanguages->getMessagesWithoutVariables( $code );
		$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
		$wgLanguages->outputMessagesList( $messagesWithoutVariables, $code, "\n$messagesWithoutVariablesNumber messages of $messagesNumber in $code don't use some variables while en uses them:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Empty messages
	if ( in_array( 'empty', $wgChecks ) ) {
		$emptyMessages = $wgLanguages->getEmptyMessages( $code );
		$emptyMessagesNumber = count( $emptyMessages );
		$wgLanguages->outputMessagesList( $emptyMessages, $code, "\n$emptyMessagesNumber messages of $messagesNumber in $code are empty or -:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages with whitespace
	if ( in_array( 'whitespace', $wgChecks ) ) {
		$messagesWithWhitespace = $wgLanguages->getMessagesWithWhitespace( $code );
		$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
		$wgLanguages->outputMessagesList( $messagesWithWhitespace, $code, "\n$messagesWithWhitespaceNumber messages of $messagesNumber in $code have a trailing whitespace:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Non-XHTML messages
	if ( in_array( 'xhtml', $wgChecks ) ) {
		$nonXHTMLMessages = $wgLanguages->getNonXHTMLMessages( $code );
		$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
		$wgLanguages->outputMessagesList( $nonXHTMLMessages, $code, "\n$nonXHTMLMessagesNumber messages of $messagesNumber in $code are not well-formed XHTML:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}

	# Messages with wrong characters
	if ( in_array( 'chars', $wgChecks ) ) {
		$messagesWithWrongChars = $wgLanguages->getMessagesWithWrongChars( $code );
		$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
		$wgLanguages->outputMessagesList( $messagesWithWrongChars, $code, "\n$messagesWithWrongCharsNumber messages of $messagesNumber in $code include hidden chars which should not be used in the messages:", $wgDisplayLevel, $wgLinks, $wgWikiLanguage );
	}
}
?>
