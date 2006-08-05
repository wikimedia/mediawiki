<?php
/**
 * Run this script to check a specific language file. If you don't specify
 * the language code, the script will run on the default installation.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

require_once( 'commandLine.inc' );
require_once( 'languages.inc' );

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

# Get messages number
$englishMessagesNumber = count( $wgLanguages->getMessagesFor( 'en' ) );
$localMessagesNumber = count( $wgLanguages->getMessagesFor( $code ) );

# Show numbers of defined messages
echo "There are $englishMessagesNumber messages in en.\n";
echo "There are $localMessagesNumber messages in $code.\n";

# Untranslated messages
$untranslatedMessages = $wgLanguages->getUntranslatedMessages( $code );
$untranslatedMessagesNumber = count( $untranslatedMessages );
$wgLanguages->outputMessagesList( $untranslatedMessages, "\n$untranslatedMessagesNumber messages of $englishMessagesNumber are not translated to $code, but exist in en:" );

# Duplicate messages
$duplicateMessages = $wgLanguages->getDuplicateMessages( $code );
$duplicateMessagesNumber = count( $duplicateMessages );
$wgLanguages->outputMessagesList( $duplicateMessages, "\n$duplicateMessagesNumber messages of $localMessagesNumber are translated the same in en and $code:" );

# Obsolete messages
$obsoleteMessages = $wgLanguages->getObsoleteMessages( $code );
$obsoleteMessagesNumber = count( $obsoleteMessages );
$wgLanguages->outputMessagesList( $obsoleteMessages, "\n$obsoleteMessagesNumber messages of $localMessagesNumber are not exist in en, but still exist in $code:" );

?>
