<?php
/**
 * Check for articles to fix after adding/deleting namespaces
 *
 * Copyright © 2005-2007 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
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
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\LinksUpdate\LinksDeletionUpdate;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\LikeValue;

/**
 * Maintenance script that checks for articles to fix after
 * adding/deleting namespaces.
 *
 * @ingroup Maintenance
 */
class NamespaceDupes extends Maintenance {

	/**
	 * Total number of pages that need fixing that are automatically resolveable
	 * @var int
	 */
	private $resolvablePages = 0;

	/**
	 * Total number of pages that need fixing
	 * @var int
	 */
	private $totalPages = 0;

	/**
	 * Total number of links that need fixing that are automatically resolveable
	 * @var int
	 */
	private $resolvableLinks = 0;

	/**
	 * Total number of erroneous links
	 * @var int
	 */
	private $totalLinks = 0;

	/**
	 * Total number of links deleted because they weren't automatically resolveable due to the
	 * target already existing
	 * @var int
	 */
	private $deletedLinks = 0;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Find and fix pages affected by namespace addition/removal' );
		$this->addOption( 'fix', 'Attempt to automatically fix errors and delete broken links' );
		$this->addOption( 'merge', "Instead of renaming conflicts, do a history merge with " .
			"the correct title" );
		$this->addOption( 'add-suffix', "Dupes will be renamed with correct namespace with " .
			"<text> appended after the article name", false, true );
		$this->addOption( 'add-prefix', "Dupes will be renamed with correct namespace with " .
			"<text> prepended before the article name", false, true );
		$this->addOption( 'source-pseudo-namespace', "Move all pages with the given source " .
			"prefix (with an implied colon following it). If --dest-namespace is not specified, " .
			"the colon will be replaced with a hyphen.",
			false, true );
		$this->addOption( 'dest-namespace', "In combination with --source-pseudo-namespace, " .
			"specify the namespace ID of the destination.", false, true );
		$this->addOption( 'move-talk', "If this is specified, pages in the Talk namespace that " .
			"begin with a conflicting prefix will be renamed, for example " .
			"Talk:File:Foo -> File_Talk:Foo" );
	}

	public function execute() {
		$options = [
			'fix' => $this->hasOption( 'fix' ),
			'merge' => $this->hasOption( 'merge' ),
			'add-suffix' => $this->getOption( 'add-suffix', '' ),
			'add-prefix' => $this->getOption( 'add-prefix', '' ),
			'move-talk' => $this->hasOption( 'move-talk' ),
			'source-pseudo-namespace' => $this->getOption( 'source-pseudo-namespace', '' ),
			'dest-namespace' => intval( $this->getOption( 'dest-namespace', 0 ) )
		];

		if ( $options['source-pseudo-namespace'] !== '' ) {
			$retval = $this->checkPrefix( $options );
		} else {
			$retval = $this->checkAll( $options );
		}

		if ( $retval ) {
			$this->output( "\nLooks good!\n" );
		} else {
			$this->output( "\nOh noeees\n" );
		}
	}

	/**
	 * Check all namespaces
	 *
	 * @param array $options Associative array of validated command-line options
	 *
	 * @return bool
	 */
	private function checkAll( $options ) {
		$contLang = $this->getServiceContainer()->getContentLanguage();
		$spaces = [];

		// List interwikis first, so they'll be overridden
		// by any conflicting local namespaces.
		foreach ( $this->getInterwikiList() as $prefix ) {
			$name = $contLang->ucfirst( $prefix );
			$spaces[$name] = 0;
		}

		// Now pull in all canonical and alias namespaces...
		foreach (
			$this->getServiceContainer()->getNamespaceInfo()->getCanonicalNamespaces()
			as $ns => $name
		) {
			// This includes $wgExtraNamespaces
			if ( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach ( $contLang->getNamespaces() as $ns => $name ) {
			if ( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach ( $contLang->getNamespaceAliases() as $name => $ns ) {
			$spaces[$name] = $ns;
		}

		// We'll need to check for lowercase keys as well,
		// since we're doing case-sensitive searches in the db.
		$capitalLinks = $this->getConfig()->get( MainConfigNames::CapitalLinks );
		foreach ( $spaces as $name => $ns ) {
			$moreNames = [];
			$moreNames[] = $contLang->uc( $name );
			$moreNames[] = $contLang->ucfirst( $contLang->lc( $name ) );
			$moreNames[] = $contLang->ucwords( $name );
			$moreNames[] = $contLang->ucwords( $contLang->lc( $name ) );
			$moreNames[] = $contLang->ucwordbreaks( $name );
			$moreNames[] = $contLang->ucwordbreaks( $contLang->lc( $name ) );
			if ( !$capitalLinks ) {
				foreach ( $moreNames as $altName ) {
					$moreNames[] = $contLang->lcfirst( $altName );
				}
				$moreNames[] = $contLang->lcfirst( $name );
			}
			foreach ( array_unique( $moreNames ) as $altName ) {
				if ( $altName !== $name ) {
					$spaces[$altName] = $ns;
				}
			}
		}

		// Sort by namespace index, and if there are two with the same index,
		// break the tie by sorting by name
		$origSpaces = $spaces;
		uksort( $spaces, static function ( $a, $b ) use ( $origSpaces ) {
			return $origSpaces[$a] <=> $origSpaces[$b]
				?: $a <=> $b;
		} );

		$ok = true;
		foreach ( $spaces as $name => $ns ) {
			$ok = $this->checkNamespace( $ns, $name, $options ) && $ok;
		}

		$this->output(
			"{$this->totalPages} pages to fix, " .
			"{$this->resolvablePages} were resolvable.\n\n"
		);

		foreach ( $spaces as $name => $ns ) {
			if ( $ns != 0 ) {
				/* Fix up link destinations for non-interwiki links only.
				 *
				 * For example if a page has [[Foo:Bar]] and then a Foo namespace
				 * is introduced, pagelinks needs to be updated to have
				 * page_namespace = NS_FOO.
				 *
				 * If instead an interwiki prefix was introduced called "Foo",
				 * the link should instead be moved to the iwlinks table. If a new
				 * language is introduced called "Foo", or if there is a pagelink
				 * [[fr:Bar]] when interlanguage magic links are turned on, the
				 * link would have to be moved to the langlinks table. Let's put
				 * those cases in the too-hard basket for now. The consequences are
				 * not especially severe.
				 * @fixme Handle interwiki links, and pagelinks to Category:, File:
				 * which probably need reparsing.
				 */

				$this->checkLinkTable( 'pagelinks', 'pl', $ns, $name, $options );
				$this->checkLinkTable( 'templatelinks', 'tl', $ns, $name, $options );

				// The redirect table has interwiki links randomly mixed in, we
				// need to filter those out. For example [[w:Foo:Bar]] would
				// have rd_interwiki=w and rd_namespace=0, which would match the
				// query for a conflicting namespace "Foo" if filtering wasn't done.
				$this->checkLinkTable( 'redirect', 'rd', $ns, $name, $options,
					[ 'rd_interwiki' => '' ] );
			}
		}

		$this->output(
			"{$this->totalLinks} links to fix, " .
			"{$this->resolvableLinks} were resolvable, " .
			"{$this->deletedLinks} were deleted.\n"
		);

		return $ok;
	}

	/**
	 * @return string[]
	 */
	private function getInterwikiList() {
		$result = $this->getServiceContainer()->getInterwikiLookup()->getAllPrefixes();
		return array_column( $result, 'iw_prefix' );
	}

	private function isSingleRevRedirectTo( Title $oldTitle, Title $newTitle ): bool {
		if ( !$oldTitle->isSingleRevRedirect() ) {
			return false;
		}
		$revStore = $this->getServiceContainer()->getRevisionStore();
		$rev = $revStore->getRevisionByTitle( $oldTitle, 0, IDBAccessObject::READ_LATEST );
		if ( !$rev ) {
			return false;
		}
		$content = $rev->getContent( SlotRecord::MAIN );
		if ( !$content ) {
			return false;
		}
		$target = $content->getRedirectTarget();
		return $target && $target->equals( $newTitle );
	}

	private function deletePage( Title $pageToDelete, string $reason ): Status {
		$services = $this->getServiceContainer();
		$page = $services->getWikiPageFactory()->newFromTitle( $pageToDelete );
		$user = User::newSystemUser( "Maintenance script" );
		$deletePage = $services->getDeletePageFactory()->newDeletePage( $page, $user );
		return $deletePage->deleteUnsafe( $reason );
	}

	/**
	 * Check a given prefix and try to move it into the given destination namespace
	 *
	 * @param int $ns Destination namespace id
	 * @param string $name
	 * @param array $options Associative array of validated command-line options
	 * @return bool
	 */
	private function checkNamespace( $ns, $name, $options ) {
		$targets = $this->getTargetList( $ns, $name, $options );
		$count = $targets->numRows();
		$this->totalPages += $count;
		if ( $count == 0 ) {
			return true;
		}

		$dryRunNote = $options['fix'] ? '' : ' DRY RUN ONLY';

		$ok = true;
		foreach ( $targets as $row ) {
			// Find the new title and determine the action to take

			$newTitle = $this->getDestinationTitle(
				$ns, $name, $row->page_namespace, $row->page_title );
			$logStatus = false;
			// $oldTitle is not a valid title by definition but the methods I use here
			// shouldn't care
			$oldTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
			if ( !$newTitle ) {
				if ( $options['add-prefix'] == '' && $options['add-suffix'] == '' ) {
					$logStatus = 'invalid title and --add-prefix not specified';
					$action = 'abort';
				} else {
					$action = 'alternate';
				}
			} elseif ( $newTitle->exists( IDBAccessObject::READ_LATEST ) ) {
				if ( $this->isSingleRevRedirectTo( $newTitle, $newTitle ) ) {
					// Conceptually this is the new title redirecting to the old title
					// except that the redirect target is parsed as wikitext so is actually
					// appears to redirect to itself
					$action = 'delete-new';
				} elseif ( $options['merge'] ) {
					if ( $this->canMerge( $row->page_id, $newTitle, $logStatus ) ) {
						$action = 'merge';
					} else {
						$action = 'abort';
					}
				} elseif ( $options['add-prefix'] == '' && $options['add-suffix'] == '' ) {
					$action = 'abort';
					$logStatus = 'dest title exists and --add-prefix not specified';
				} else {
					$action = 'alternate';
				}
			} else {
				$action = 'move';
				$logStatus = 'no conflict';
			}
			if ( $action === 'alternate' ) {
				[ $ns, $dbk ] = $this->getDestination( $ns, $name, $row->page_namespace,
					$row->page_title );
				$altTitle = $this->getAlternateTitle( $ns, $dbk, $options );
				if ( !$altTitle ) {
					$action = 'abort';
					$logStatus = 'alternate title is invalid';
				} elseif ( $altTitle->exists() ) {
					$action = 'abort';
					$logStatus = 'alternate title conflicts';
				} elseif ( $this->isSingleRevRedirectTo( $oldTitle, $newTitle ) ) {
					$action = 'delete-old';
					$newTitle = $altTitle;
				} else {
					$action = 'move';
					$logStatus = 'alternate';
					$newTitle = $altTitle;
				}
			}

			// Take the action or log a dry run message

			$logTitle = "id={$row->page_id} ns={$row->page_namespace} dbk={$row->page_title}";
			$pageOK = true;

			switch ( $action ) {
				case 'delete-old':
					$this->output( "$logTitle move to " . $newTitle->getPrefixedDBKey() .
						" then delete as single-revision redirect to new home$dryRunNote\n" );
					if ( $options['fix'] ) {
						// First move the page so the delete command gets a valid title
						$pageOK = $this->movePage( $row->page_id, $newTitle );
						if ( $pageOK ) {
							$status = $this->deletePage(
								$newTitle,
								"Non-normalized title already redirects to new form"
							);
							if ( !$status->isOK() ) {
								$this->error( $status );
								$pageOK = false;
							}
						}
					}
					break;
				case "delete-new":
					$this->output( "$logTitle -> " .
					$newTitle->getPrefixedDBkey() . " delete existing page $dryRunNote\n" );
					if ( $options['fix'] ) {
						$status = $this->deletePage( $newTitle, "Delete circular redirect to make way for move" );
						$pageOK = $status->isOK();
						if ( $pageOK ) {
							$pageOK = $this->movePage( $row->page_id, $newTitle );
						} else {
							$this->error( $status );
						}
					}
					break;
				case 'abort':
					$this->output( "$logTitle *** $logStatus\n" );
					$pageOK = false;
					break;
				case 'move':
					$this->output( "$logTitle -> " .
						$newTitle->getPrefixedDBkey() . " ($logStatus)$dryRunNote\n" );

					if ( $options['fix'] ) {
						$pageOK = $this->movePage( $row->page_id, $newTitle );
					}
					break;
				case 'merge':
					$this->output( "$logTitle => " .
						$newTitle->getPrefixedDBkey() . " (merge)$dryRunNote\n" );

					if ( $options['fix'] ) {
						$pageOK = $this->mergePage( $row, $newTitle );
					}
					break;
			}

			if ( $pageOK ) {
				$this->resolvablePages++;
			} else {
				$ok = false;
			}
		}

		return $ok;
	}

	/**
	 * Check and repair the destination fields in a link table
	 * @param string $table The link table name
	 * @param string $fieldPrefix The field prefix in the link table
	 * @param int $ns Destination namespace id
	 * @param string $name
	 * @param array $options Associative array of validated command-line options
	 * @param array $extraConds Extra conditions for the SQL query
	 */
	private function checkLinkTable( $table, $fieldPrefix, $ns, $name, $options,
		$extraConds = []
	) {
		$dbw = $this->getPrimaryDB();

		$batchConds = [];
		$fromField = "{$fieldPrefix}_from";
		$batchSize = 100;
		$sqb = $dbw->newSelectQueryBuilder()
			->select( $fromField )
			->where( $extraConds )
			->limit( $batchSize );

		$linksMigration = $this->getServiceContainer()->getLinksMigration();
		if ( isset( $linksMigration::$mapping[$table] ) ) {
			$sqb->queryInfo( $linksMigration->getQueryInfo( $table ) );
			[ $namespaceField, $titleField ] = $linksMigration->getTitleFields( $table );
			$schemaMigrationStage = $linksMigration::$mapping[$table]['config'] === -1
				? MIGRATION_NEW
				: $this->getConfig()->get( $linksMigration::$mapping[$table]['config'] );
			$linkTargetLookup = $this->getServiceContainer()->getLinkTargetLookup();
			$targetIdField = $linksMigration::$mapping[$table]['target_id'];
		} else {
			$sqb->table( $table );
			$namespaceField = "{$fieldPrefix}_namespace";
			$titleField = "{$fieldPrefix}_title";
			$sqb->fields( [ $namespaceField, $titleField ] );
			// Variables only used for links migration, init only
			$schemaMigrationStage = -1;
			$linkTargetLookup = null;
			$targetIdField = '';
		}
		$sqb->andWhere( [
				$namespaceField => 0,
				$dbw->expr( $titleField, IExpression::LIKE, new LikeValue( "$name:", $dbw->anyString() ) ),
			] )
			->orderBy( [ $titleField, $fromField ] )
			->caller( __METHOD__ );

		$updateRowsPerQuery = $this->getConfig()->get( MainConfigNames::UpdateRowsPerQuery );
		while ( true ) {
			$res = ( clone $sqb )
				->andWhere( $batchConds )
				->fetchResultSet();
			if ( $res->numRows() == 0 ) {
				break;
			}

			$rowsToDeleteIfStillExists = [];

			foreach ( $res as $row ) {
				$logTitle = "from={$row->$fromField} ns={$row->$namespaceField} " .
					"dbk={$row->$titleField}";
				$destTitle = $this->getDestinationTitle(
					$ns, $name, $row->$namespaceField, $row->$titleField );
				$this->totalLinks++;
				if ( !$destTitle ) {
					$this->output( "$table $logTitle *** INVALID\n" );
					continue;
				}
				$this->resolvableLinks++;
				if ( !$options['fix'] ) {
					$this->output( "$table $logTitle -> " .
						$destTitle->getPrefixedDBkey() . " DRY RUN\n" );
					continue;
				}

				if ( isset( $linksMigration::$mapping[$table] ) ) {
					$setValue = [];
					if ( $schemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) {
						$setValue[$targetIdField] = $linkTargetLookup->acquireLinkTargetId( $destTitle, $dbw );
					}
					if ( $schemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) {
						$setValue["{$fieldPrefix}_namespace"] = $destTitle->getNamespace();
						$setValue["{$fieldPrefix}_title"] = $destTitle->getDBkey();
					}
					$whereCondition = $linksMigration->getLinksConditions(
						$table,
						new TitleValue( 0, $row->$titleField )
					);
					$deleteCondition = $linksMigration->getLinksConditions(
						$table,
						new TitleValue( (int)$row->$namespaceField, $row->$titleField )
					);
				} else {
					$setValue = [
						$namespaceField => $destTitle->getNamespace(),
						$titleField => $destTitle->getDBkey()
					];
					$whereCondition = [
						$namespaceField => 0,
						$titleField => $row->$titleField
					];
					$deleteCondition = [
						$namespaceField => $row->$namespaceField,
						$titleField => $row->$titleField,
					];
				}

				$dbw->newUpdateQueryBuilder()
					->update( $table )
					->ignore()
					->set( $setValue )
					->where( [ $fromField => $row->$fromField ] )
					->andWhere( $whereCondition )
					->caller( __METHOD__ )
					->execute();

				// In case there is a key conflict on UPDATE IGNORE the row needs deletion
				$rowsToDeleteIfStillExists[] = array_merge( [ $fromField => $row->$fromField ], $deleteCondition );

				$this->output( "$table $logTitle -> " .
					$destTitle->getPrefixedDBkey() . "\n"
				);
			}

			if ( $options['fix'] && count( $rowsToDeleteIfStillExists ) > 0 ) {
				$affectedRows = 0;
				$deleteBatches = array_chunk( $rowsToDeleteIfStillExists, $updateRowsPerQuery );
				foreach ( $deleteBatches as $deleteBatch ) {
					$dbw->newDeleteQueryBuilder()
						->deleteFrom( $table )
						->where( $dbw->factorConds( $deleteBatch ) )
						->caller( __METHOD__ )
						->execute();
					$affectedRows += $dbw->affectedRows();
					if ( count( $deleteBatches ) > 1 ) {
						$this->waitForReplication();
					}
				}

				$this->deletedLinks += $affectedRows;
				$this->resolvableLinks -= $affectedRows;
			}

			$batchConds = [
				$dbw->buildComparison( '>', [
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
					$titleField => $row->$titleField,
					// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
					$fromField => $row->$fromField,
				] )
			];

			$this->waitForReplication();
		}
	}

	/**
	 * Move the given pseudo-namespace, either replacing the colon with a hyphen
	 * (useful for pseudo-namespaces that conflict with interwiki links) or move
	 * them to another namespace if specified.
	 * @param array $options Associative array of validated command-line options
	 * @return bool
	 */
	private function checkPrefix( $options ) {
		$prefix = $options['source-pseudo-namespace'];
		$ns = $options['dest-namespace'];
		$this->output( "Checking prefix \"$prefix\" vs namespace $ns\n" );

		return $this->checkNamespace( $ns, $prefix, $options );
	}

	/**
	 * Find pages in main and talk namespaces that have a prefix of the new
	 * namespace so we know titles that will need migrating
	 *
	 * @param int $ns Destination namespace id
	 * @param string $name Prefix that is being made a namespace
	 * @param array $options Associative array of validated command-line options
	 *
	 * @return IResultWrapper
	 */
	private function getTargetList( $ns, $name, $options ) {
		$dbw = $this->getPrimaryDB();

		if (
			$options['move-talk'] &&
			$this->getServiceContainer()->getNamespaceInfo()->isSubject( $ns )
		) {
			$checkNamespaces = [ NS_MAIN, NS_TALK ];
		} else {
			$checkNamespaces = NS_MAIN;
		}

		return $dbw->newSelectQueryBuilder()
			->select( [ 'page_id', 'page_title', 'page_namespace' ] )
			->from( 'page' )
			->where( [
				'page_namespace' => $checkNamespaces,
				$dbw->expr( 'page_title', IExpression::LIKE, new LikeValue( "$name:", $dbw->anyString() ) ),
			] )
			->caller( __METHOD__ )->fetchResultSet();
	}

	/**
	 * Get the preferred destination for a given target page.
	 * @param int $ns The destination namespace ID
	 * @param string $name The conflicting prefix
	 * @param int $sourceNs The source namespace
	 * @param string $sourceDbk The source DB key (i.e. page_title)
	 * @return array [ ns, dbkey ], not necessarily valid
	 */
	private function getDestination( $ns, $name, $sourceNs, $sourceDbk ) {
		$dbk = substr( $sourceDbk, strlen( "$name:" ) );
		if ( $ns <= 0 ) {
			// An interwiki or an illegal namespace like "Special" or "Media"
			// try an alternate encoding with '-' for ':'
			$dbk = "$name-" . $dbk;
			$ns = 0;
		}
		$destNS = $ns;
		$nsInfo = $this->getServiceContainer()->getNamespaceInfo();
		if ( $sourceNs == NS_TALK && $nsInfo->isSubject( $ns ) ) {
			// This is an associated talk page moved with the --move-talk feature.
			$destNS = $nsInfo->getTalk( $destNS );
		}
		return [ $destNS, $dbk ];
	}

	/**
	 * Get the preferred destination title for a given target page.
	 * @param int $ns The destination namespace ID
	 * @param string $name The conflicting prefix
	 * @param int $sourceNs The source namespace
	 * @param string $sourceDbk The source DB key (i.e. page_title)
	 * @return Title|false
	 */
	private function getDestinationTitle( $ns, $name, $sourceNs, $sourceDbk ) {
		[ $destNS, $dbk ] = $this->getDestination( $ns, $name, $sourceNs, $sourceDbk );
		$newTitle = Title::makeTitleSafe( $destNS, $dbk );
		if ( !$newTitle || !$newTitle->canExist() ) {
			return false;
		}
		return $newTitle;
	}

	/**
	 * Get an alternative title to move a page to. This is used if the
	 * preferred destination title already exists.
	 *
	 * @param int $ns The destination namespace ID
	 * @param string $dbk The source DB key (i.e. page_title)
	 * @param array $options Associative array of validated command-line options
	 * @return Title|false
	 */
	private function getAlternateTitle( $ns, $dbk, $options ) {
		$prefix = $options['add-prefix'];
		$suffix = $options['add-suffix'];
		if ( $prefix == '' && $suffix == '' ) {
			return false;
		}
		$newDbk = $prefix . $dbk . $suffix;
		return Title::makeTitleSafe( $ns, $newDbk );
	}

	/**
	 * Move a page
	 *
	 * @param int $id The page_id
	 * @param LinkTarget $newLinkTarget The new title link target
	 * @return bool
	 */
	private function movePage( $id, LinkTarget $newLinkTarget ) {
		$dbw = $this->getPrimaryDB();

		$dbw->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [
				"page_namespace" => $newLinkTarget->getNamespace(),
				"page_title" => $newLinkTarget->getDBkey(),
			] )
			->where( [
				"page_id" => $id,
			] )
			->caller( __METHOD__ )
			->execute();

		// Update *_from_namespace in links tables
		$fromNamespaceTables = [
			[ 'templatelinks', 'tl', [ 'tl_target_id' ] ],
			[ 'imagelinks', 'il', [ 'il_to' ] ]
		];
		if ( $this->getConfig()->get( MainConfigNames::PageLinksSchemaMigrationStage ) & SCHEMA_COMPAT_WRITE_OLD ) {
			$fromNamespaceTables[] = [ 'pagelinks', 'pl', [ 'pl_namespace', 'pl_title' ] ];
		} else {
			$fromNamespaceTables[] = [ 'pagelinks', 'pl', [ 'pl_target_id' ] ];
		}
		$updateRowsPerQuery = $this->getConfig()->get( MainConfigNames::UpdateRowsPerQuery );
		foreach ( $fromNamespaceTables as [ $table, $fieldPrefix, $additionalPrimaryKeyFields ] ) {
			$fromField = "{$fieldPrefix}_from";
			$fromNamespaceField = "{$fieldPrefix}_from_namespace";

			$res = $dbw->newSelectQueryBuilder()
				->select( $additionalPrimaryKeyFields )
				->from( $table )
				->where( [ $fromField => $id ] )
				->andWhere( $dbw->expr( $fromNamespaceField, '!=', $newLinkTarget->getNamespace() ) )
				->caller( __METHOD__ )
				->fetchResultSet();
			if ( !$res ) {
				continue;
			}

			$updateConds = [];
			foreach ( $res as $row ) {
				$updateConds[] = array_merge( [ $fromField => $id ], (array)$row );
			}
			$updateBatches = array_chunk( $updateConds, $updateRowsPerQuery );
			foreach ( $updateBatches as $updateBatch ) {
				$this->beginTransactionRound( __METHOD__ );
				$dbw->newUpdateQueryBuilder()
					->update( $table )
					->set( [ $fromNamespaceField => $newLinkTarget->getNamespace() ] )
					->where( $dbw->factorConds( $updateBatch ) )
					->caller( __METHOD__ )
					->execute();
				$this->commitTransactionRound( __METHOD__ );
			}
		}

		return true;
	}

	/**
	 * Determine if we can merge a page.
	 * We check if an inaccessible revision would become the latest and
	 * deny the merge if so -- it's theoretically possible to update the
	 * latest revision, but opens a can of worms -- search engine updates,
	 * recentchanges review, etc.
	 *
	 * @param int $id The page_id
	 * @param PageIdentity $page
	 * @param string &$logStatus This is set to the log status message on failure @phan-output-reference
	 * @return bool
	 */
	private function canMerge( $id, PageIdentity $page, &$logStatus ) {
		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$latestDest = $revisionLookup->getRevisionByTitle( $page, 0,
			IDBAccessObject::READ_LATEST );
		$latestSource = $revisionLookup->getRevisionByPageId( $id, 0,
			IDBAccessObject::READ_LATEST );
		if ( $latestSource->getTimestamp() > $latestDest->getTimestamp() ) {
			$logStatus = 'cannot merge since source is later';
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Merge page histories
	 *
	 * @param stdClass $row Page row
	 * @param Title $newTitle
	 * @return bool
	 */
	private function mergePage( $row, Title $newTitle ) {
		$updateRowsPerQuery = $this->getConfig()->get( MainConfigNames::UpdateRowsPerQuery );

		$id = $row->page_id;

		// Construct the WikiPage object we will need later, while the
		// page_id still exists. Note that this cannot use makeTitleSafe(),
		// we are deliberately constructing an invalid title.
		$sourceTitle = Title::makeTitle( $row->page_namespace, $row->page_title );
		$sourceTitle->resetArticleID( $id );
		$wikiPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $sourceTitle );
		$wikiPage->loadPageData( IDBAccessObject::READ_LATEST );
		$destId = $newTitle->getArticleID();

		$dbw = $this->getPrimaryDB();
		$this->beginTransactionRound( __METHOD__ );
		$revIds = $dbw->newSelectQueryBuilder()
			->select( 'rev_id' )
			->from( 'revision' )
			->where( [ 'rev_page' => $id ] )
			->caller( __METHOD__ )
			->fetchFieldValues();
		$updateBatches = array_chunk( array_map( 'intval', $revIds ), $updateRowsPerQuery );
		foreach ( $updateBatches as $updateBatch ) {
			$dbw->newUpdateQueryBuilder()
				->update( 'revision' )
				->set( [ 'rev_page' => $destId ] )
				->where( [ 'rev_id' => $updateBatch ] )
				->caller( __METHOD__ )
				->execute();
			if ( count( $updateBatches ) > 1 ) {
				$this->commitTransactionRound( __METHOD__ );
				$this->beginTransactionRound( __METHOD__ );
			}
		}
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'page' )
			->where( [ 'page_id' => $id ] )
			->caller( __METHOD__ )
			->execute();
		$this->commitTransactionRound( __METHOD__ );

		/* Call LinksDeletionUpdate to delete outgoing links from the old title,
		 * and update category counts.
		 *
		 * Calling external code with a fake broken Title is a fairly dubious
		 * idea. It's necessary because it's quite a lot of code to duplicate,
		 * but that also makes it fragile since it would be easy for someone to
		 * accidentally introduce an assumption of title validity to the code we
		 * are calling.
		 */
		DeferredUpdates::addUpdate( new LinksDeletionUpdate( $wikiPage ) );
		DeferredUpdates::doUpdates();

		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = NamespaceDupes::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
