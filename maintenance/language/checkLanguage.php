<?php
/**
 * Check a language file.
 *
 * @addtogroup Maintenance
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'checkLanguage.inc' );

# Show help
if ( isset( $options['help'] ) ) {
	echo <<<END
Run this script to check a specific language file, or all of them.
Parameters:
	* lang: Language code (default: the installation default language). You can also specify "all" to check all the languages.
	* help: Show this help.
	* level: Show the following level (default: 2).
	* links: Link the message values (default off).
	* wikilang: For the links, what is the content language of the wiki to display the output in (default en).
	* whitelist: Make only the following checks (form: code,code).
	* blacklist: Don't make the following checks (form: code,code).
	* duplicate: Additionally check for messages which are translated the same to English (default off).
	* plural: Additionally check for messages that don't use plural while English does (default off).
	* noexif: Don't check for EXIF messages (a bit hard and boring to translate), if you know that they are currently not translated and want to focus on other problems (default off).
Check codes (ideally, all of them should result 0; all the checks are executed by default):
	* untranslated: Messages which are required to translate, but are not translated.
	* obsolete: Messages which are untranslatable, but translated.
	* variables: Messages without variables which should be used.
	* empty: Empty messages.
	* whitespace: Messages which have trailing whitespace.
	* xhtml: Messages which are not well-formed XHTML.
	* chars: Messages with hidden characters.
Display levels (default: 2):
	* 0: Skip the checks (useful for checking syntax).
	* 1: Show only the stub headers and number of wrong messages, without list of messages.
	* 2: Show only the headers and the message keys, without the message values.
	* 3: Show both the headers and the complete messages, with both keys and values.

END;
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

# Get the links options
$wgLinks = isset( $options['links'] );
$wgWikiLanguage = isset( $options['wikilang'] ) ? $options['wikilang'] : 'en';

# Get the checks to do
$wgChecks = array( 'untranslated', 'obsolete', 'variables', 'empty', 'whitespace', 'xhtml', 'chars' );
if ( isset( $options['whitelist'] ) ) {
	$wgChecks = explode( ',', $options['whitelist'] );
} elseif ( isset( $options['blacklist'] ) ) {
	$wgChecks = array_diff( $wgChecks, explode( ',', $options['blacklist'] ) );
}

# Add duplicate and plural options if specified
if ( isset( $options['duplicate'] ) ) {
	$wgChecks[] = 'duplicate';
}
if ( isset( $options['plural'] ) ) {
	$wgChecks[] = 'plural';
}

# Should check for EXIF?
$wgCheckEXIF = !isset( $options['noexif'] );

# Get language objects
$wgLanguages = new languages( $wgCheckEXIF );

# Get the general messages
$wgGeneralMessages = $wgLanguages->getGeneralMessages();
$wgRequiredMessagesNumber = count( $wgGeneralMessages['required'] );

# Check the language
if ( $wgCode == 'all' ) {
	foreach ( $wgLanguages->getLanguages() as $language ) {
		if ( $language != 'en' && $language != 'enRTL' ) {
			checkLanguage( $wgLanguages, $language );
		}
	}
} else {
	# Can't check English
	if ( $wgCode == 'en' ) {
		echo "Current selected language is English, which cannot be checked.\n";
	} else if ( $wgCode == 'enRTL' ) {
		echo "Current selected language is RTL English, which cannot be checked.\n";
	} else {
		checkLanguage( $wgLanguages, $wgCode );
	}
}

?>
