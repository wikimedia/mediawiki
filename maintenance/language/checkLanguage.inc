<?php
/**
 * Check a language.
 *
 * @param $languages The languages object.
 * @param $code The language code (default content language of the wiki running the script on).
 * @param: $links Show wiki links to messages (default false)?
 * @param: $wikiLanguage Language of the wiki to show the output in, if showing links (default en).
 * @param: $checks Checks to do (default all except for duplicates and plural).
 * @return Number of errors found.
 */
function checkLanguage( $languages, $code = null, $displayLevel = 2, $links = false, $wikiLanguage = 'en', $checks = null ) {
	# Get messages
	$messages = $languages->getMessages( $code );
	$messagesNumber = count( $messages['translated'] );

	# Skip the checks if told so
	if ( $displayLevel == 0 ) {
		return;
	}

	# Initialize counts
	$problems = 0;

	# Set default language code and checks
	if ( !$code ) {
		global $wgContLang;
		$code = $wgContLang->getCode();
	}
	if ( !$checks ) {
		$checks = array( 'untranslated', 'obsolete', 'variables', 'empty', 'whitespace', 'xhtml', 'chars' );
	}

	# Untranslated messages
	if ( in_array( 'untranslated', $checks ) ) {
		$generalMessages = $languages->getGeneralMessages();
		$requiredMessagesNumber = count( $generalMessages['required'] );
		$untranslatedMessages = $languages->getUntranslatedMessages( $code );
		$untranslatedMessagesNumber = count( $untranslatedMessages );
		$languages->outputMessagesList( $untranslatedMessages, $code, "\n$untranslatedMessagesNumber messages of $requiredMessagesNumber are not translated to $code, but exist in en:", $displayLevel, $links, $wikiLanguage );
		$problems += $untranslatedMessagesNumber;
	}

	# Duplicate messages
	if ( in_array( 'duplicate', $checks ) ) {
		$duplicateMessages = $languages->getDuplicateMessages( $code );
		$duplicateMessagesNumber = count( $duplicateMessages );
		$languages->outputMessagesList( $duplicateMessages, $code, "\n$duplicateMessagesNumber messages of $messagesNumber are translated the same in en and $code:", $displayLevel, $links, $wikiLanguage );
		$problems += $duplicateMessagesNumber;
	}

	# Obsolete messages
	if ( in_array( 'obsolete', $checks ) ) {
		$obsoleteMessages = $messages['obsolete'];
		$obsoleteMessagesNumber = count( $obsoleteMessages );
		$languages->outputMessagesList( $obsoleteMessages, $code, "\n$obsoleteMessagesNumber messages of $messagesNumber do not exist in en (or are in the ignored list), but still exist in $code:", $displayLevel, $links, $wikiLanguage );
		$problems += $obsoleteMessagesNumber;
	}

	# Messages without variables
	if ( in_array( 'variables', $checks ) ) {
		$messagesWithoutVariables = $languages->getMessagesWithoutVariables( $code );
		$messagesWithoutVariablesNumber = count( $messagesWithoutVariables );
		$languages->outputMessagesList( $messagesWithoutVariables, $code, "\n$messagesWithoutVariablesNumber messages of $messagesNumber in $code don't use some variables while en uses them:", $displayLevel, $links, $wikiLanguage );
		$problems += $messagesWithoutVariablesNumber;
	}

	# Messages without plural
	if ( in_array( 'plural', $checks ) ) {
		$messagesWithoutPlural = $languages->getMessagesWithoutPlural( $code );
		$messagesWithoutPluralNumber = count( $messagesWithoutPlural );
		$languages->outputMessagesList( $messagesWithoutPlural, $code, "\n$messagesWithoutPluralNumber messages of $messagesNumber in $code don't use {{plural}} while en uses it:", $displayLevel, $links, $wikiLanguage );
		$problems += $messagesWithoutPluralNumber;
	}

	# Empty messages
	if ( in_array( 'empty', $checks ) ) {
		$emptyMessages = $languages->getEmptyMessages( $code );
		$emptyMessagesNumber = count( $emptyMessages );
		$languages->outputMessagesList( $emptyMessages, $code, "\n$emptyMessagesNumber messages of $messagesNumber in $code are empty or -:", $displayLevel, $links, $wikiLanguage );
		$problems += $emptyMessagesNumber;
	}

	# Messages with whitespace
	if ( in_array( 'whitespace', $checks ) ) {
		$messagesWithWhitespace = $languages->getMessagesWithWhitespace( $code );
		$messagesWithWhitespaceNumber = count( $messagesWithWhitespace );
		$languages->outputMessagesList( $messagesWithWhitespace, $code, "\n$messagesWithWhitespaceNumber messages of $messagesNumber in $code have a trailing whitespace:", $displayLevel, $links, $wikiLanguage );
		$problems += $messagesWithWhitespaceNumber;
	}

	# Non-XHTML messages
	if ( in_array( 'xhtml', $checks ) ) {
		$nonXHTMLMessages = $languages->getNonXHTMLMessages( $code );
		$nonXHTMLMessagesNumber = count( $nonXHTMLMessages );
		$languages->outputMessagesList( $nonXHTMLMessages, $code, "\n$nonXHTMLMessagesNumber messages of $messagesNumber in $code are not well-formed XHTML:", $displayLevel, $links, $wikiLanguage );
		$problems += $nonXHTMLMessagesNumber;
	}

	# Messages with wrong characters
	if ( in_array( 'chars', $checks ) ) {
		$messagesWithWrongChars = $languages->getMessagesWithWrongChars( $code );
		$messagesWithWrongCharsNumber = count( $messagesWithWrongChars );
		$languages->outputMessagesList( $messagesWithWrongChars, $code, "\n$messagesWithWrongCharsNumber messages of $messagesNumber in $code include hidden chars which should not be used in the messages:", $displayLevel, $links, $wikiLanguage );
		$problems += $messagesWithWrongCharsNumber;
	}

	return $problems;
}
