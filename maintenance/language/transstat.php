<?php
/**
 * Statistics about the localisation.
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
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Ashar Voultoiz <hashar at free dot fr>
 *
 * Output is posted from time to time on:
 * http://www.mediawiki.org/wiki/Localisation_statistics
 */
$optionsWithArgs = array( 'output' );

require_once( dirname( __FILE__ ) . '/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( dirname( __FILE__ ) . '/StatOutputs.php' );


if ( isset( $options['help'] ) ) {
	showUsage();
}

# Default output is WikiText
if ( !isset( $options['output'] ) ) {
	$options['output'] = 'wiki';
}

/** Print a usage message*/
function showUsage() {
	print <<<TEXT
Usage: php transstat.php [--help] [--output=csv|text|wiki]
	--help : this helpful message
	--output : select an output engine one of:
		* 'csv'      : Comma Separated Values.
		* 'wiki'     : MediaWiki syntax (default).
		* 'text'     : Text with tabs.
Example: php maintenance/transstat.php --output=text

TEXT;
	exit( 1 );
}



# Select an output engine
switch ( $options['output'] ) {
	case 'wiki':
		$output = new wikiStatsOutput();
		break;
	case 'text':
		$output = new textStatsOutput();
		break;
	case 'csv':
		$output = new csvStatsOutput();
		break;
	default:
		showUsage();
}

# Languages
$wgLanguages = new languages();

# Header
$output->heading();
$output->blockstart();
$output->element( 'Language', true );
$output->element( 'Code', true );
$output->element( 'Fallback', true );
$output->element( 'Translated', true );
$output->element( '%', true );
$output->element( 'Obsolete', true );
$output->element( '%', true );
$output->element( 'Problematic', true );
$output->element( '%', true );
$output->blockend();

$wgGeneralMessages = $wgLanguages->getGeneralMessages();
$wgRequiredMessagesNumber = count( $wgGeneralMessages['required'] );

foreach ( $wgLanguages->getLanguages() as $code ) {
	# Don't check English or RTL English
	if ( $code == 'en' || $code == 'enRTL' ) {
		continue;
	}

	# Calculate the numbers
	$language = $wgContLang->getLanguageName( $code );
	$fallback = $wgLanguages->getFallback( $code );
	$messages = $wgLanguages->getMessages( $code );
	$messagesNumber = count( $messages['translated'] );
	$requiredMessagesNumber = count( $messages['required'] );
	$requiredMessagesPercent = $output->formatPercent( $requiredMessagesNumber, $wgRequiredMessagesNumber );
	$obsoleteMessagesNumber = count( $messages['obsolete'] );
	$obsoleteMessagesPercent = $output->formatPercent( $obsoleteMessagesNumber, $messagesNumber, true );
	$messagesWithMismatchVariables = $wgLanguages->getMessagesWithMismatchVariables( $code );
	$emptyMessages = $wgLanguages->getEmptyMessages( $code );
	$messagesWithWhitespace = $wgLanguages->getMessagesWithWhitespace( $code );
	$nonXHTMLMessages = $wgLanguages->getNonXHTMLMessages( $code );
	$messagesWithWrongChars = $wgLanguages->getMessagesWithWrongChars( $code );
	$problematicMessagesNumber = count( array_unique( array_merge( $messagesWithMismatchVariables, $emptyMessages, $messagesWithWhitespace, $nonXHTMLMessages, $messagesWithWrongChars ) ) );
	$problematicMessagesPercent = $output->formatPercent( $problematicMessagesNumber, $messagesNumber, true );

	# Output them
	$output->blockstart();
	$output->element( "$language" );
	$output->element( "$code" );
	$output->element( "$fallback" );
	$output->element( "$requiredMessagesNumber/$wgRequiredMessagesNumber" );
	$output->element( $requiredMessagesPercent );
	$output->element( "$obsoleteMessagesNumber/$messagesNumber" );
	$output->element( $obsoleteMessagesPercent );
	$output->element( "$problematicMessagesNumber/$messagesNumber" );
	$output->element( $problematicMessagesPercent );
	$output->blockend();
}

# Footer
$output->footer();


