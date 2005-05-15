<?php
/** Greek (Ελληνικά)
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

$wgNamespaceNamesEl = array(
	NS_MEDIA            => 'Μέσον',
	NS_SPECIAL          => 'Ειδικό',
	NS_MAIN	            => '',
	NS_TALK	            => 'Συζήτηση',
	NS_USER             => 'Χρήστης',
	NS_USER_TALK        => 'Συζήτηση_χρήστη',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_συζήτηση',
	NS_IMAGE            => 'Εικόνα',
	NS_IMAGE_TALK       => 'Συζήτηση_εικόνας',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Φόρμα',
	NS_TEMPLATE_TALK    => 'Συζήτηση_φόρμας',
	NS_HELP             => 'Βοήθεια',
	NS_HELP_TALK        => 'Συζήτηση_βοήθειας',
	NS_CATEGORY         => 'Κατηγορία',
	NS_CATEGORY_TALK    => 'Συζήτηση_κατηγορίας',
) + $wgNamespaceNamesEn;

$wgAllMessagesEl = array(

'sunday' => 'Κυριακή',
'monday' => 'Δευτέρα',
'tuesday' => 'Τρίτη',
'wednesday' => 'Τετάρτη',
'thursday' => 'Πέμπτη',
'friday' => 'Παρασκευή',
'saturday' => 'Σαββάτο',

'january' => 'Ιανουαρίου',
'february' => 'Φεβρουαρίου',
'march' => 'Μαρτίου',
'april' => 'Απριλίου',
'may_long' => 'Μαΐου',
'june' => 'Ιουνίου',
'july' => 'Ιουλίου',
'august' => 'Αυγούστου',
'september' => 'Σεπτεμβρίου',
'october' => 'Οκτωβρίου',
'november' => 'Νοεμβρίου',
'december' => 'Δεκεμβρίου',

'jan' => 'Ιαν',
'feb' => 'Φεβρ',
'mar' => 'Μαρτ',
'apr' => 'Απρ',
'may' => 'Μαΐου',
'jun' => 'Ιουν',
'jul' => 'Ιουλ',
'aug' => 'Αυγ',
'sep' => 'Σεπτ',
'oct' => 'Οκτ',
'nov' => 'Νοεμβ',
'dec' => 'Δεκ',
);


class LanguageEl extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesEl;
		return $wgNamespaceNamesEl;
	}
	
	function getNsText( $index ) {
		global $wgNamespaceNamesEl;
		return $wgNamespaceNamesEl[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesEl;

		foreach ( $wgNamespaceNamesEl as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getMessage( $key ) {
		global $wgAllMessagesEl;

		return isset( $wgAllMessagesEl[$key] ) ? $wgAllMessagesEl[$key] : parent::getMessage( $key );
	}
	
	function fallback8bitEncoding() {
		return 'windows-1253';
	}
	
	function formatNum( $number ) {
		return strtr($number, '.,', ',.' );
	}
}

?>
