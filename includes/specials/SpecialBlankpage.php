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
			"wiki" => wfMessage( "wikibase-otherprojects-wikipedia" )->text(),
			"wiktionary" => "Wiktionary", // No message for this!?
			"wikibooks" => wfMessage( "wikibase-otherprojects-wikibooks" )->text(),
			"wikiquote" => wfMessage( "wikibase-otherprojects-wikiquote" )->text(),
			"wikinews" => wfMessage( "wikibase-otherprojects-wikinews" )->text(),
			"wikisource" => wfMessage( "wikibase-sitelinks-wikisource" )->text(),
			"wikiversity" => "Wikiversity", // No message for this, either!
			"wikivoyage" => wfMessage( "wikibase-otherprojects-wikivoyage" )->text(),
		);

		$failedLangWikis = array(
			"azbwiki" => "South Azerbaijani Wikipedia",
			"be_x_oldwiki" => "Belarusian (Taraškievica) Wikipedia",
			"bhwiki" => "Bihari Wikipedia",
			"bhwiktionary" => "Bihari Wiktionary",
			"bxrwiki" => "Buryat Wikipedia",
			"lbewiki" => "Laki Wikipedia",
			"mowiki" => "Moldovan Wikipedia",
			"mowiktionary" => "Moldovan Wiktionary",
			"tarawiki" => "Tarandíne Wikipedia",
			"zh_min_nanwiki" => "Min Nan Wikipedia",
			"zh_min_nanwiktionary" => "Min Nan Wiktionary",
			"zh_min_nanwikibooks" => "Min Nan Wikibooks",
			"zh_min_nanwikiquote" => "Min Nan Wikiquote",
			"zh_min_nanwikisource" => "Min Nan Wikisource",
		);


		// SPECIAL WIKIS
		$exceptions = array(
			// code => full message
			"commons" => wfMessage( "wikibase-otherprojects-commons" )->text(),
			"mediawiki" => wfMessage( "wikibase-otherprojects-mediawiki" )->text(),
			"meta" => wfMessage( "wikibase-otherprojects-meta" )->text(),
			"wikidata" => wfMessage( "wikibase-otherprojects-wikidata" )->text(),
			"sources" => wfMessage( "wikibase-otherprojects-wikisource" )->text(),
			"species" => wfMessage( "wikibase-sitelinks-sitename-species" )->text(),
			"strategy" => "Strategic Planning",
			"ten" => "Wikipedia 10",
			"test" => "Test Wikipedia",
			"test2" => "Test2 Wikipedia",
			"zero" => "Wikimedia Zero",
			"board" => "Wikimedia Board Wiki",
			"labswiki" => "Wikitech",
			"labtestwiki" => "Wikitech Test Wiki",
			"testwikidata" => wfMessage( "wikibase-otherprojects-testwikidata" )->text(),
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
			"bdwikimedia" => "Wikimedia Bangladesh",
			"cnwikimedia" => "Wikimedia China",
			"ilwikimedia" => "Wikimedia Israel",
			"mkwikimedia" => "Wikimedia Macedonia",
			"mxwikimedia" => "Wikimedia Mexico",
			"rswikimedia" => "Wikimedia Serbia",
			"ruwikimedia" => "Wikimedia Russia",
			"trwikimedia" => "Wikimedia Turkey",
			"uawikimedia" => "Wikimedia Ukraine",
			"nlwikimedia" => "Wikimedia Netherlands",
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

					if ( array_key_exists( $dbname, $failedLangWikis ) ) {
						$name = $failedLangWikis[ $dbname ];
					} else {
						$sitecode = $sites[ $j ]['code'];
						$sitename = !empty( $messageMatrix[ $sitecode ] ) ? $messageMatrix[ $sitecode ] : $sites[ $j ]['sitename'];
						// Language conversion
						$lang = $languages[ $langCode ];

						$name = $lang . " " . $sitename;
					}

					// Output the line
					$outEn[ "project-localized-name-" . $dbname ] =  $name;
					$outQqq[ "project-localized-name-" . $dbname ] =  $this->createQQQ( $name, $url );

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
				$outEn[ "project-localized-name-" . $dbname ] =  $sitename;
				$outQqq[ "project-localized-name-" . $dbname ] =  $this->createQQQ( $sitename, $url );

				$counter++;
			// }
		}

		// Output
		$this->getOutput()->addWikiText( $counter );
		$this->getOutput()->addWikiText( '= en.json =' );
		$this->getOutput()->addWikiText( json_encode( $outEn, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) );
		$this->getOutput()->addWikiText( '= qqq.json =' );
		$this->getOutput()->addHTML( '<pre>'.json_encode( $outQqq, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ).'</pre>' );
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
