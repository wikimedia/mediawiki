<?php

/**
 * Holds sites for testing purposes.
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
 * @since 1.20
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TestSites {

	/**
	 * @since 1.20
	 *
	 * @return array
	 */
	public static function getSites() {
		$sites = array();

		$site = Sites::newSite( 'foobar' );
		$sites[] = $site;

		$site = Sites::newSite( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setType( Site::TYPE_MEDIAWIKI );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites[] = $site;

		$site = Sites::newSite( 'dewiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setType( Site::TYPE_MEDIAWIKI );
		$site->setLanguageCode( 'de' );
		$site->addInterwikiId( 'dewiktionary' );
		$site->addInterwikiId( 'wiktionaryde' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://de.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://de.wiktionary.org/w/$1" );
		$sites[] = $site;

		$site = Sites::newSite( 'spam' );
		$site->setGroup( 'spam' );
		$site->setType( Site::TYPE_UNKNOWN );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'spam' );
		$site->addNavigationId( 'spamz' );
		$site->addInterwikiId( 'spamzz' );
		$site->setLinkPath( "http://spamzz.test/testing/" );
		$sites[] = $site;

		foreach ( array( 'en', 'de', 'nl', 'sv', 'sr', 'no', 'nn' ) as $langCode ) {
			$site = Sites::newSite( $langCode . 'wiki' );
			$site->setGroup( 'wikipedia' );
			$site->setType( Site::TYPE_MEDIAWIKI );
			$site->setLanguageCode( $langCode );
			$site->addInterwikiId( $langCode );
			$site->addNavigationId( $langCode );
			$site->setPath( MediaWikiSite::PATH_PAGE, "https://$langCode.wikipedia.org/wiki/$1" );
			$site->setPath( MediaWikiSite::PATH_FILE, "https://$langCode.wikipedia.org/w/$1" );
			$sites[] = $site;
		}

		return $sites;
	}

	/**
	 * Inserts sites into the database for the unit tests that need them.
	 *
	 * @since 0.1
	 */
	public static function insertIntoDb() {
		$dbw = wfGetDB( DB_MASTER );

		$dbw->begin( __METHOD__ );

		$dbw->delete( 'sites', '*', __METHOD__ );
		$dbw->delete( 'site_identifiers', '*', __METHOD__ );

		/**
		 * @var Site $site
		 */
		foreach ( TestSites::getSites() as $site ) {
			$site->save();
		}

		$dbw->commit( __METHOD__ );

		Sites::singleton()->getSites( false ); // re-cache
	}

}