<?php
/**
 * Rewrite the messages array in the files languages/messages/MessagesXx.php.
 *
 * @file
 * @ingroup MaintenanceLanguage
 * @defgroup MaintenanceLanguage MaintenanceLanguage
 */

require_once( dirname( __FILE__ ) . '/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'writeMessagesArray.inc' );

/**
 * Rewrite a messages array.
 *
 * @param $code The language code.
 * @param $write Write to the messages file?
 * @param $listUnknown List the unknown messages?
 * @param $removeUnknown Remove the unknown messages?
 * @param $removeDupes Remove the duplicated messages?
 * @param $dupeMsgSource The source file intended to remove from the array.
 */
function rebuildLanguage( $code, $write, $listUnknown, $removeUnknown, $removeDupes, $dupeMsgSource ) {
	global $wgLanguages;
	$messages = $wgLanguages->getMessages( $code );
	$messages = $messages['all'];
	if ( $removeDupes ) {
		$messages = removeDupes( $messages, $dupeMsgSource );
	}
	MessageWriter::writeMessagesToFile( $messages, $code, $write, $listUnknown, $removeUnknown );
}

/**
 * Remove duplicates from a message array.
 *
 * @param $oldMsgArray The input message array.
 * @param $dupeMsgSource The source file path for duplicates.
 * @return $newMsgArray The output message array, with duplicates removed.
 */
function removeDupes( $oldMsgArray, $dupeMsgSource ) {
	if ( file_exists( $dupeMsgSource ) ) {
		include( $dupeMsgSource );
		if ( !isset( $dupeMessages ) ) {
			echo( "There are no duplicated messages in the source file provided." );
			exit( 1 );
		}
	} else {
		echo ( "The specified file $dupeMsgSource cannot be found." );
		exit( 1 );
	}
	$newMsgArray = $oldMsgArray;
	foreach ( $oldMsgArray as $key => $value ) {
		if ( array_key_exists( $key, $dupeMessages ) ) {
			unset( $newMsgArray[$key] );
		}
	}
	return $newMsgArray;
}

# Show help
if ( isset( $options['help'] ) ) {
	echo <<<TEXT
Run this script to rewrite the messages array in the files languages/messages/MessagesXX.php.
Parameters:
	* lang: Language code (default: the installation default language). You can also specify "all" to check all the languages.
	* help: Show this help.
Options:
	* dry-run: Do not write the array to the file.
	* no-unknown: Do not list the unknown messages.
	* remove-unknown: Remove unknown messages.
	* remove-duplicates: Remove duplicated messages based on a PHP source file.

TEXT;
	exit( 1 );
}

# Get the language code
if ( isset( $options['lang'] ) ) {
	$wgCode = $options['lang'];
} else {
	$wgCode = $wgContLang->getCode();
}

# Get the duplicate message source
if ( isset( $options['remove-duplicates'] ) && ( strcmp( $options['remove-duplicates'], '' ) ) ) {
	$wgDupeMessageSource = $options['remove-duplicates'];
} else {
	$wgDupeMessageSource = '';
}

# Get the options
$wgWriteToFile = !isset( $options['dry-run'] );
$wgListUnknownMessages = !isset( $options['no-unknown'] );
$wgRemoveUnknownMessages = isset( $options['remove-unknown'] );
$wgRemoveDuplicateMessages = isset( $options['remove-duplicates'] );

# Get language objects
$wgLanguages = new languages();

# Write all the language
if ( $wgCode == 'all' ) {
	foreach ( $wgLanguages->getLanguages() as $language ) {
		rebuildLanguage( $language, $wgWriteToFile, $wgListUnknownMessages, $wgRemoveUnknownMessages, $wgRemoveDuplicateMessages, $wgDupeMessageSource );
	}
} else {
	rebuildLanguage( $wgCode, $wgWriteToFile, $wgListUnknownMessages, $wgRemoveUnknownMessages, $wgRemoveDuplicateMessages, $wgDupeMessageSource );
}
