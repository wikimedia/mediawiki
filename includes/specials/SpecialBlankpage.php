<?php
/**
 * Implements Special:Blankpage
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
 * @ingroup SpecialPage
 */

/**
 * Special page designed for basic benchmarking of
 * MediaWiki since it doesn't really do much.
 *
 * @ingroup SpecialPage
 */
class SpecialBlankpage extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Blankpage' );
	}

	private function createQQQ( $projName, $projURL ) {
		return "{{ProjectNameDocumentation|url=$projURL|name=$projName}}";
	}

	public function execute( $par ) {
		global $wgConf;

		// *********************************************************************
		// THIS IS NOT FOR MERGING!!!!!
		// *********************************************************************
		// This script is strictly for a **one-time operation** to produce a list
		// of i18n key:value pairs for the initial list of human-readable
		// project names across the WMF production cluster.
		// *********************************************************************
		$this->setHeaders();

		$outEn = array();
		$outQqq = array();
		$counter = 0;
		$info = $this->getSitematrixFromAPI();
		$languages = LanguageNames::getNames( 'en' );

		// Wikis that have messages in WikimediaMessages
		$messageMatrix = array(
			// code => project name
			"wiki" => wfMessage( "wikibase-otherprojects-wikipedia" ),
			"wiktionary" => "Wiktionary", // No message for this!?
			"wikibooks" => wfMessage( "wikibase-otherprojects-wikibooks" ),
			"wikiquote" => wfMessage( "wikibase-otherprojects-wikiquote" ),
			"wikinews" => wfMessage( "wikibase-otherprojects-wikinews" ),
			"wikisource" => wfMessage( "wikibase-sitelinks-wikisource" ),
			"wikiversity" => "Wikiversity", // No message for this, either!
			"wikivoyage" => wfMessage( "wikibase-otherprojects-wikivoyage" ),
		);

		// SPECIAL WIKIS
		$exceptions = array(
			// code => full message
			"commons" => wfMessage( "wikibase-otherprojects-commons" ),
			"mediawiki" => wfMessage( "wikibase-otherprojects-mediawiki" ),
			"meta" => wfMessage( "wikibase-otherprojects-meta" ),
			"wikidata" => wfMessage( "wikibase-otherprojects-wikidata" ),
			"sources" => wfMessage( "wikibase-otherprojects-wikisource" ),
			"species" => wfMessage( "wikibase-sitelinks-sitename-species" ),
			"strategy" => "Strategic Planning",
			"ten" => "Wikipedia 10",
			"test" => "Test Wikipedia",
			"test2" => "Test2 Wikipedia",
			"zero" => "Wikimedia Zero",
			"testwikidata" => wfMessage( "wikibase-otherprojects-testwikidata" ),
			"wikimania2005" => "Wikimania 2005 Wiki",
			"wikimania2006" => "Wikimania 2006 Wiki",
			"wikimania2007" => "Wikimania 2007 Wiki",
			"wikimania2008" => "Wikimania 2008 Wiki",
			"wikimania2009" => "Wikimania 2009 Wiki",
			"wikimania2010" => "Wikimania 2010 Wiki",
			"wikimania2011" => "Wikimania 2011 Wiki",
			"wikimania2012" => "Wikimania 2012 Wiki",
			"wikimania2013" => "Wikimania 2013 Wiki",
			"wikimania2014" => "Wikimania 2014 Wiki",
			"wikimania2015" => "Wikimania 2015 Wiki",
			"wikimania2016" => "Wikimania 2016 Wiki",
			"wikimania2017" => "Wikimania 2017 Wiki",
		);

		// Go over the wikis and fill in the information
		for ( $i = 0; $i < count( $info ); $i++ ) {
			$langCode = $info[ $i ]['code'];
			// Go by each site
			$sites = $info[ $i ]['site'];
			for ( $j = 0; $j < count( $sites ); $j++ ) {
				// Skip projects marked as "closed"
				// if ( !isset( $sites[ $j ]['closed'] ) ) {
					// Wiki info
					$dbname = $sites[ $j ]['dbname'];
					$url = $sites[ $j ]['url'];

					$sitecode = $sites[ $j ]['code'];
					$sitename = !empty( $messageMatrix[ $sitecode ] ) ? $messageMatrix[ $sitecode ] : $sites[ $j ]['sitename'];

					// Language conversion
					$lang = $languages[ $langCode ];
					// Output the line
					$outEn[ "\"project-humanreadable-name-" . $dbname ] =  $lang . " " . $sitename;
					$outQqq[ "\"project-humanreadable-name-" . $dbname ] =  $this->createQQQ( $lang . " " . $sitename, $url );

					$counter++;
				// }
			}
		}

		// Go over the "special" wikis (exceptions)
		$specials = $info[ "specials" ];
		for ( $i = 0; $i < count( $specials ); $i++ ) {
			// Skip projects marked as "closed"
			// if ( !isset( $specials[ $j ]['closed'] ) ) {
				$dbname = $specials[ $i ]['dbname'];
				$url = $specials[ $i ]['url'];
				$sitecode = $specials[ $i ]['code'];
				// Go over exceptions
				if ( array_key_exists( $sitecode, $exceptions ) ) {
					$sitename = $exceptions[ $sitecode ];
				} else {
					// Output the line
					$sitename = $specials[ $i ]['sitename'];
				}

				// Output the line
				$outEn[ "\"project-humanreadable-name-" . $dbname ] =  $sitename;
				$outQqq[ "\"project-humanreadable-name-" . $dbname ] =  $this->createQQQ( $sitename, $url );

				$counter++;
			// }
		}

		// Output
		$this->getOutput()->addWikiText( $counter );
		$this->getOutput()->addWikiText( '= en.json =' );
		$this->getOutput()->addWikiText( json_encode( $outEn, JSON_PRETTY_PRINT ) );
		$this->getOutput()->addWikiText( '= qqq.json =' );
		$this->getOutput()->addHTML( json_encode( $outQqq, JSON_PRETTY_PRINT ) );
	}

	private function getSitematrixFromAPI() {
		$curl = curl_init();
		$url = "https://en.wikipedia.org/w/api.php?action=sitematrix&format=json&formatversion=2";

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$response = json_decode( curl_exec($curl), true );
		curl_close($curl);

		return $response['sitematrix'];
	}
}
