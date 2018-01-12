<?php

namespace MediaWiki\Storage;

use MWException;
use Wikimedia\Rdbms\IDatabase;

trait DatabaseWikiIdChecker {

	/**
	 * Throws an exception if the given database connection does not belong to the wiki this
	 * RevisionStore is bound to.
	 *
	 * @param IDatabase $db
	 * @param string $wikiId
	 * @throws MWException
	 */
	private function checkDatabaseWikiId( IDatabase $db, $wikiId ) {
		$dbWiki = $db->getDomainID();

		if ( $dbWiki === $wikiId ) {
			return;
		}

		// XXX: we really want the default database ID...
		$wikiId = $wikiId ?: wfWikiID();
		$dbWiki = $dbWiki ?: wfWikiID();

		if ( $dbWiki === $wikiId ) {
			return;
		}

		// HACK: counteract encoding imposed by DatabaseDomain
		$wikiId = str_replace( '?h', '-', $wikiId );
		$dbWiki = str_replace( '?h', '-', $dbWiki );

		if ( $dbWiki === $wikiId ) {
			return;
		}

		throw new MWException( "RevisionStore for $wikiId "
							   . "cannot be used with a DB connection for $dbWiki" );
	}

}
