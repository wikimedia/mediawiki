<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Tests\Site;

use MediaWiki\MediaWikiServices;
use MediaWiki\Site\MediaWikiSite;
use MediaWiki\Site\Site;

/**
 * Holds sites for testing purposes, re-used in the Wikibase extension.
 *
 * @since 1.21
 * @ingroup Site
 * @ingroup Test
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TestSites {

	public static function getSites(): array {
		$sites = [];

		$site = new Site();
		$site->setGlobalId( 'foobar' );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'enwiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'enwiktionary' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://en.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://en.wiktionary.org/w/$1" );
		$sites[] = $site;

		$site = new MediaWikiSite();
		$site->setGlobalId( 'dewiktionary' );
		$site->setGroup( 'wiktionary' );
		$site->setLanguageCode( 'de' );
		$site->addInterwikiId( 'dewiktionary' );
		$site->addInterwikiId( 'wiktionaryde' );
		$site->setPath( MediaWikiSite::PATH_PAGE, "https://de.wiktionary.org/wiki/$1" );
		$site->setPath( MediaWikiSite::PATH_FILE, "https://de.wiktionary.org/w/$1" );
		$sites[] = $site;

		$site = new Site();
		$site->setGlobalId( 'spam' );
		$site->setGroup( 'spam' );
		$site->setLanguageCode( 'en' );
		$site->addNavigationId( 'spam' );
		$site->addNavigationId( 'spamz' );
		$site->addInterwikiId( 'spamzz' );
		$site->setLinkPath( "http://spamzz.test/testing/" );
		$sites[] = $site;

		/**
		 * Add at least one right-to-left language (current RTL languages in MediaWiki core are:
		 * aeb, ar, arc, arz, azb, bcc, bqi, ckb, dv, en_rtl, fa, glk, he, khw, kk_arab, kk_cn,
		 * ks_arab, ku_arab, lrc, mzn, pnb, ps, sd, ug_arab, ur, yi).
		 */
		$languageCodes = [
			'de',
			'en',
			'fa', // right-to-left
			'nl',
			'nn',
			'no',
			'sr',
			'sv',
		];
		foreach ( $languageCodes as $langCode ) {
			$site = new MediaWikiSite();
			$site->setGlobalId( $langCode . 'wiki' );
			$site->setGroup( 'wikipedia' );
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
	 */
	public static function insertIntoDb() {
		$sitesTable = MediaWikiServices::getInstance()->getSiteStore();
		$sitesTable->clear();
		$sitesTable->saveSites( self::getSites() );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( TestSites::class, 'TestSites' );
