<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

#
# Latin-1 compatibility layer hack.
#
# Enable by setting "$wgUseLatin1 = true;" in LocalSettings.php
# (Preferably at install time so you get the messages right!)
#
# This will replace anything that can't be described in Latin-1 with
# an ugly question mark (?) so don't use this mode on languages that
# aren't suited to it!

# This file and LanguageUtf8.php may be included from within functions, so
# we need to have global statements
global $wgInputEncoding, $wgOutputEncoding; 

$wgInputEncoding = "ISO-8859-1";
$wgOutputEncoding = "ISO-8859-1";

function utf8_decode_array( $arr ) {
	if( !is_array( $arr ) ) {
		wfDebugDieBacktrace( "utf8_decode_array given non-array" );
	}
	return array_map( "utf8_decode", $arr );
}

#
# This is a proxy object; the Language instance handed to us speaks
# UTF-8, while the wiki outside speaks Latin-1. We translate as
# necessary so neither knows the other is in the wrong charset.
#
class LanguageLatin1 {
	var $lang;
	
	function LanguageLatin1( &$language ) {
		$this->lang =& $language;
	}
	
	function getDefaultUserOptions() {
		return $this->lang->getDefaultUserOptions();
	}
	
	function getBookstoreList() {
		return utf8_decode_array( $this->lang->getBookstoreList() );
	}
	
	function getNamespaces() {
		return utf8_decode_array( $this->lang->getNamespaces() );
	}
	
	function getNsText( $index ) {
		return utf8_decode( $this->lang->getNsText( $index ) );
	}
	
	function getNsIndex( $text ) {
		return $this->lang->getNsIndex( utf8_encode( $text ) );
	}
	
	function specialPage( $name ) {
        # At least one function calls this with Special:Undelete/Article_title, so it needs encoding
		return utf8_decode( $this->lang->specialPage( utf8_encode( $name ) ) );
	}
	
	function getQuickbarSettings() {
		return utf8_decode_array( $this->lang->getQuickbarSettings() );
	}
	
	function getSkinNames() {
		return utf8_decode_array( $this->lang->getSkinNames() );
	}
	
	function getMathNames() {
		return utf8_decode_array( $this->lang->getMathNames() );
	}
	
	function getDateFormats() {
		return utf8_decode_array( $this->lang->getDateFormats() );
	}
	
	function getUserToggles() {
		return utf8_decode_array( $this->lang->getUserToggles() );
	}
	
	function getUserToggle( $tog ) {
		return utf8_decode( $this->lang->getUserToggle( $tog ) );
	}
	
	function getLanguageNames() {
		return utf8_decode_array( $this->lang->getLanguageNames() );
	}
	
	function getLanguageName( $code ) {
		return utf8_decode( $this->lang->getLanguageName( $code ) );
	}
	
	function getMonthName( $key ) {
		return utf8_decode( $this->lang->getMonthName( $key ) );
	}
	
	function getMonthNameGen( $key ) {
		return utf8_decode( $this->lang->getMonthNameGen( $key ) );
	}
	
	function getMonthAbbreviation( $key ) {
		return utf8_decode( $this->lang->getMonthAbbreviation( $key ) );
	}
	
	function getWeekdayName( $key ) {
		return utf8_decode( $this->lang->getWeekdayName( $key ) );
	}
	
	function userAdjust( $ts ) {
		return $this->lang->userAdjust( $ts );
	}
	
	function date( $ts, $adj = false ) {
		return utf8_decode( $this->lang->date( $ts, $adj ) );
	}
	
	function time( $ts, $adj = false, $seconds = false ) {
		return utf8_decode( $this->lang->time( $ts, $adj ) );
	}
	
	function timeanddate( $ts, $adj = false ) {
		return utf8_decode( $this->lang->timeanddate( $ts, $adj ) );
	}
	
	function rfc1123( $ts ) {
		# ASCII by definition
		return $this->lang->rfc1123( $ts );
	}
	
	function getValidSpecialPages() {
		return utf8_decode_array( $this->lang->getValidSpecialPages() );
	}
	
	function getSysopSpecialPages() {
		return utf8_decode_array( $this->lang->getSysopSpecialPages() );
	}
	
	function getDeveloperSpecialPages() {
		return utf8_decode_array( $this->lang->getDeveloperSpecialPages() );
	}
	
	function getMessage( $key ) {
		return utf8_decode( $this->lang->getMessage( $key ) );
	}
	
	function getAllMessages() {
		return utf8_decode_array( $this->lang->getAllMessages() );
	}
	
	function iconv( $in, $out, $string ) {
		# Use 8-bit version
		return Language::iconv( $in, $out, $string );
	}
	
	function ucfirst( $string ) {
		# Use 8-bit version
		return Language::ucfirst( $string );
	}
	
	function lcfirst( $s ) {
		# Use 8-bit version
		return Language::lcfirst( $s );
	}
	
	function checkTitleEncoding( $s ) {
		# Use 8-bit version
		return Language::checkTitleEncoding( $s );
	}
	
	function stripForSearch( $in ) {
		# Use 8-bit version
		return Language::stripForSearch( $in );
	}
	
	function firstChar( $s ) {
		# Use 8-bit version
		return Language::firstChar( $s );
	}
	
	function setAltEncoding() {
		# Not sure if this should be handled
		$this->lang->setAltEncoding();
	}
	
	function recodeForEdit( $s ) {
		# Use 8-bit version
		return Language::recodeForEdit( $s );
	}
	
	function recodeInput( $s ) {
		# Use 8-bit version
		return Language::recodeInput( $s );
	}
	
	function isRTL() {
		# boolean
		return $this->lang->isRTL();
	}
	
	function linkPrefixExtension() {
		# boolean
		return $this->lang->linkPrefixExtension();
	}
	
	function &getMagicWords() {
		return utf8_decode_array( $this->lang->getMagicWords() );
	}
	
	function getMagic( &$mw ) {
		# Not sure how to handle this.
		# A moot point perhaps as few language files currently
		# assign localised magic words, and none of the ones we
		# need backwards compatibility for.
		return $this->lang->getMagic( $mw );
	}
	
	function emphasize( $text ) {
		# It's unlikely that the emphasis markup itself will
		# include any non-ASCII chars.
		return $this->lang->emphasize( $text );
	}
	
	function formatNum( $number ) {
		# Probably not necessary...
		return utf8_decode( $this->lang->formatNum( $number ) );
	}
	
	function listToText( $l ) {
		# It's unlikely that the list markup itself will
		# include any non-ASCII chars. (?)
		return $this->lang->listToText( $l );
	}

	function truncate( $string, $length, $ellipsis = "" ) {
		return Language::truncate( $string, $length, $ellipsis );
	}

	function convertGrammar( $word, $case ) {
		return $word;
	}

	function getPreferredVariant() {
		return $this->lang->getPreferredVariant();
	}

	function segmentForDiff( $text ) {
		return $text;
	}

	function unsegmentForDiff( $text ) {
		return $text;
	}

	function convert( $text, $isTitle=false ) {
		return utf8_decode( $this->lang->convert( utf8_encode( $text ), $isTitle ) );
	}
	
	function autoConvert($text, $toVariant=false) {
		return utf8_decode( $this->lang->autoConvert( utf8_encode( $text ), $toVariant ) );
	}

	/* hook for converting the title, which may needs special treatment
	*/
	function convertTitle($text) {
		return utf8_decode( $this->lang->convertTitle( utf8_encode( $text ) ) );
	}

	function getVariants() {
		return $this->lang->getVariants();
	}

	function convertForSearchResult( $termsArray ) {
		return $termsArray;
	}	

	function getExtraHashOptions() {
		return array();
	}

}

?>
