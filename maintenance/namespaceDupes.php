<?php
/**
 * Check for articles to fix after adding/deleting namespaces
 *
 * Copyright © 2005-2007 Brion Vibber <brion@pobox.com>
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that checks for articles to fix after
 * adding/deleting namespaces.
 *
 * @ingroup Maintenance
 */
class NamespaceConflictChecker extends Maintenance {

	/**
	 * @var DatabaseBase
	 */
	protected $db;

	private $resolvableCount = 0;
	private $totalPages = 0;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "";
		$this->addOption( 'fix', 'Attempt to automatically fix errors' );
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
		$this->db = wfGetDB( DB_MASTER );

		$options = array(
			'fix' => $this->hasOption( 'fix' ),
			'merge' => $this->hasOption( 'merge' ),
			'add-suffix' => $this->getOption( 'add-suffix', '' ),
			'add-prefix' => $this->getOption( 'add-prefix', '' ),
			'move-talk' => $this->hasOption( 'move-talk' ),
			'source-pseudo-namespace' => $this->getOption( 'source-pseudo-namespace', '' ),
			'dest-namespace' => intval( $this->getOption( 'dest-namespace', 0 ) ) );

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
		global $wgContLang, $wgNamespaceAliases, $wgCapitalLinks;

		$spaces = array();

		// List interwikis first, so they'll be overridden
		// by any conflicting local namespaces.
		foreach ( $this->getInterwikiList() as $prefix ) {
			$name = $wgContLang->ucfirst( $prefix );
			$spaces[$name] = 0;
		}

		// Now pull in all canonical and alias namespaces...
		foreach ( MWNamespace::getCanonicalNamespaces() as $ns => $name ) {
			// This includes $wgExtraNamespaces
			if ( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach ( $wgContLang->getNamespaces() as $ns => $name ) {
			if ( $name !== '' ) {
				$spaces[$name] = $ns;
			}
		}
		foreach ( $wgNamespaceAliases as $name => $ns ) {
			$spaces[$name] = $ns;
		}
		foreach ( $wgContLang->getNamespaceAliases() as $name => $ns ) {
			$spaces[$name] = $ns;
		}

		// We'll need to check for lowercase keys as well,
		// since we're doing case-sensitive searches in the db.
		foreach ( $spaces as $name => $ns ) {
			$moreNames = array();
			$moreNames[] = $wgContLang->uc( $name );
			$moreNames[] = $wgContLang->ucfirst( $wgContLang->lc( $name ) );
			$moreNames[] = $wgContLang->ucwords( $name );
			$moreNames[] = $wgContLang->ucwords( $wgContLang->lc( $name ) );
			$moreNames[] = $wgContLang->ucwordbreaks( $name );
			$moreNames[] = $wgContLang->ucwordbreaks( $wgContLang->lc( $name ) );
			if ( !$wgCapitalLinks ) {
				foreach ( $moreNames as $altName ) {
					$moreNames[] = $wgContLang->lcfirst( $altName );
				}
				$moreNames[] = $wgContLang->lcfirst( $name );
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
		uksort( $spaces, function ( $a, $b ) use ( $origSpaces ) {
			if ( $origSpaces[$a] < $origSpaces[$b] ) {
				return -1;
			} elseif ( $origSpaces[$a] > $origSpaces[$b] ) {
				return 1;
			} elseif ( $a < $b ) {
				return -1;
			} elseif ( $a > $b ) {
				return 1;
			} else {
				return 0;
			}
		} );

		$ok = true;
		foreach ( $spaces as $name => $ns ) {
			$ok = $this->checkNamespace( $ns, $name, $options ) && $ok;
		}

		$this->output( "{$this->totalPages} pages to fix, " .
			"{$this->resolvableCount} were resolvable.\n" );

		return $ok;
	}

	/**
	 * Get the interwiki list
	 *
	 * @return array
	 */
	private function getInterwikiList() {
		$result = Interwiki::getAllPrefixes();
		$prefixes = array();
		foreach ( $result as $row ) {
			$prefixes[] = $row['iw_prefix'];
		}

		return $prefixes;
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

			$newTitle = $this->getDestinationTitle( $ns, $name, $row, $options );
			$logStatus = false;
			if ( !$newTitle ) {
				$logStatus = 'invalid title';
				$action = 'abort';
			} elseif ( $newTitle->exists() ) {
				if ( $options['merge'] ) {
					if ( $this->canMerge( $row->page_id, $newTitle, $logStatus ) ) {
						$action = 'merge';
					} else {
						$action = 'abort';
					}
				} elseif ( $options['add-prefix'] == '' && $options['add-suffix'] == '' ) {
					$action = 'abort';
					$logStatus = 'dest title exists and --add-prefix not specified';
				} else {
					$newTitle = $this->getAlternateTitle( $newTitle, $options );
					if ( !$newTitle ) {
						$action = 'abort';
						$logStatus = 'alternate title is invalid';
					} elseif ( $newTitle->exists() ) {
						$action = 'abort';
						$logStatus = 'title conflict';
					} else {
						$action = 'move';
						$logStatus = 'alternate';
					}
				}
			} else {
				$action = 'move';
				$logStatus = 'no conflict';
			}

			// Take the action or log a dry run message

			$logTitle = "id={$row->page_id} ns={$row->page_namespace} dbk={$row->page_title}";
			$pageOK = true;

			switch ( $action ) {
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
						$pageOK = $this->mergePage( $row->page_id, $newTitle );
					}
					break;
			}

			if ( $pageOK ) {
				$this->resolvableCount++;
			} else {
				$ok = false;
			}
		}

		// @fixme Also needs to do like self::getTargetList() on the
		// *_namespace and *_title fields of pagelinks, templatelinks, and
		// redirects, and schedule a LinksUpdate job or similar for each found
		// *_from.

		return $ok;
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
	 * @return ResultWrapper
	 */
	private function getTargetList( $ns, $name, $options ) {
		if ( $options['move-talk'] && MWNamespace::isSubject( $ns ) ) {
			$checkNamespaces = array( NS_MAIN, NS_TALK );
		} else {
			$checkNamespaces = NS_MAIN;
		}

		return $this->db->select( 'page',
			array(
				'page_id',
				'page_title',
				'page_namespace',
			),
			array(
				'page_namespace' => $checkNamespaces,
				'page_title' . $this->db->buildLike( "$name:", $this->db->anyString() ),
			),
			__METHOD__
		);
	}

	/**
	 * Get the preferred destination title for a given target page row.
	 * @param integer $ns The destination namespace ID
	 * @param string $name The conflicting prefix
	 * @param stdClass $row
	 * @param array $options Associative array of validated command-line options
	 * @return Title|false
	 */
	private function getDestinationTitle( $ns, $name, $row, $options ) {
		$dbk = substr( $row->page_title, strlen( "$name:" ) );
		if ( $ns == 0 ) {
			// An interwiki; try an alternate encoding with '-' for ':'
			$dbk = "$name-" . $dbk;
		}
		$destNS = $ns;
		if ( $row->page_namespace == NS_TALK && MWNamespace::isSubject( $ns ) ) {
			// This is an associated talk page moved with the --move-talk feature.
			$destNS = MWNamespace::getTalk( $destNS );
		}
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
	 * @param Title $title
	 * @param array $options Associative array of validated command-line options
	 * @return Title|bool
	 */
	private function getAlternateTitle( $title, $options ) {
		$prefix = $options['add-prefix'];
		$suffix = $options['add-suffix'];
		if ( $prefix == '' && $suffix == '' ) {
			return false;
		}
		while ( true ) {
			$dbk = $prefix . $title->getDBkey() . $suffix;
			$title = Title::makeTitleSafe( $title->getNamespace(), $dbk );
			if ( !$title ) {
				return false;
			}
			if ( !$title->exists() ) {
				return $title;
			}
		}
	}

	/**
	 * Move a page
	 *
	 * @fixme Update pl_from_namespace etc.
	 *
	 * @param integer $id The page_id
	 * @param Title $newTitle The new title
	 * @return bool
	 */
	private function movePage( $id, Title $newTitle ) {
		$this->db->update( 'page',
			array(
				"page_namespace" => $newTitle->getNamespace(),
				"page_title" => $newTitle->getDBkey(),
			),
			array(
				"page_id" => $id,
			),
			__METHOD__ );

		// @fixme Needs updating the *_from_namespace fields in categorylinks,
		// pagelinks, templatelinks and imagelinks.

		return true;
	}

	/**
	 * Determine if we can merge a page.
	 * We check if an inaccessible revision would become the latest and
	 * deny the merge if so -- it's theoretically possible to update the
	 * latest revision, but opens a can of worms -- search engine updates,
	 * recentchanges review, etc.
	 *
	 * @param integer $id The page_id
	 * @param Title $newTitle The new title
	 * @param string $logStatus This is set to the log status message on failure
	 * @return bool
	 */
	private function canMerge( $id, Title $newTitle, &$logStatus ) {
		$latestDest = Revision::newFromTitle( $newTitle, 0, Revision::READ_LATEST );
		$latestSource = Revision::newFromPageId( $id, 0, Revision::READ_LATEST );
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
	 * @param integer $id The page_id
	 * @param Title $newTitle The new title
	 */
	private function mergePage( $id, Title $newTitle ) {
		$destId = $newTitle->getArticleId();
		$this->db->begin( __METHOD__ );
		$this->db->update( 'revision',
			// SET
			array( 'rev_page' => $destId ),
			// WHERE
			array( 'rev_page' => $id ),
			__METHOD__ );

		$this->db->delete( 'page', array( 'page_id' => $id ), __METHOD__ );

		// @fixme Need WikiPage::doDeleteUpdates() or similar to avoid orphan
		// rows in the links tables.

		$this->db->commit( __METHOD__ );
		return true;
	}
}

$maintClass = "NamespaceConflictChecker";
require_once RUN_MAINTENANCE_IF_MAIN;
