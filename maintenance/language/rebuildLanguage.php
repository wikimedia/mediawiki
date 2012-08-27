<?php
/**
 * Rewrite the messages array in the files languages/messages/MessagesXx.php.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup MaintenanceLanguage
 * @defgroup MaintenanceLanguage MaintenanceLanguage
 */

require_once( __DIR__ . '/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'writeMessagesArray.inc' );

/**
 * Rewrite a messages array.
 *
 * @param $languages
 * @param $code string The language code.
 * @param bool $write Write to the messages file?
 * @param bool $listUnknown List the unknown messages?
 * @param bool $removeUnknown Remove the unknown messages?
 * @param bool $removeDupes Remove the duplicated messages?
 * @param $dupeMsgSource string The source file intended to remove from the array.
 * @param $messagesFolder String: path to a folder to store the MediaWiki messages.
 */
function rebuildLanguage( $languages, $code, $write, $listUnknown, $removeUnknown, $removeDupes, $dupeMsgSource, $messagesFolder ) {
	$messages = $languages->getMessages( $code );
	$messages = $messages['all'];
	if ( $removeDupes ) {
		$messages = removeDupes( $messages, $dupeMsgSource );
	}
	MessageWriter::writeMessagesToFile( $messages, $code, $write, $listUnknown, $removeUnknown, $messagesFolder );
}

/**
 * Remove duplicates from a message array.
 *
 * @param $oldMsgArray array The input message array.
 * @param $dupeMsgSource string The source file path for duplicates.
 * @return Array $newMsgArray The output message array, with duplicates removed.
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
	* messages-folder: An alternative folder with MediaWiki messages.

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
$messagesFolder = isset( $options['messages-folder'] ) ? $options['messages-folder'] : false;

# Get language objects
$languages = new languages();

# Write all the language
if ( $wgCode == 'all' ) {
	foreach ( $languages->getLanguages() as $languageCode ) {
		rebuildLanguage( $languages, $languageCode, $wgWriteToFile, $wgListUnknownMessages, $wgRemoveUnknownMessages, $wgRemoveDuplicateMessages, $wgDupeMessageSource, $messagesFolder );
	}
} else {
	rebuildLanguage( $languages, $wgCode, $wgWriteToFile, $wgListUnknownMessages, $wgRemoveUnknownMessages, $wgRemoveDuplicateMessages, $wgDupeMessageSource, $messagesFolder );
}
