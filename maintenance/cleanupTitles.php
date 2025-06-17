<?php
/**
 * Clean up broken, unparseable titles.
 *
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
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
 * @author Brooke Vibber <bvibber@wikimedia.org>
 * @ingroup Maintenance
 */

use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IDBAccessObject;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/TableCleanup.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to clean up broken, unparseable titles.
 *
 * @ingroup Maintenance
 */
class TitleCleanup extends TableCleanup {

	private string $prefix;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to clean up broken, unparseable titles' );
		$this->addOption( 'prefix', "Broken pages will be renamed to titles with  " .
			"<prefix> prepended before the article name. Defaults to 'Broken'", false, true );
		$this->setBatchSize( 1000 );
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		$this->prefix = $this->getOption( 'prefix', 'Broken' ) . "/";
		// Make sure the prefix itself is a valid title now
		// rather than spewing errors for every page being cleaned up
		// if it's not (We assume below that concatenating the prefix to a title leaves it in NS0)
		// The trailing slash above ensures that concatenating the title to something
		// can't turn it into a namespace or interwiki
		$title = Title::newFromText( $this->prefix );
		if ( !$title || !$title->canExist() || $title->getInterwiki() || $title->getNamespace() !== 0 ) {
			$this->fatalError( "Invalid prefix {$this->prefix}. Must be a valid mainspace title." );
		}
		parent::execute();
	}

	/**
	 * @param stdClass $row
	 */
	protected function processRow( $row ) {
		$display = Title::makeName( $row->page_namespace, $row->page_title );
		$verified = $this->getServiceContainer()->getContentLanguage()->normalize( $display );
		$title = Title::newFromText( $verified );

		if ( $title !== null
			&& $title->canExist()
			&& $title->getNamespace() == $row->page_namespace
			&& $title->getDBkey() === $row->page_title
		) {
			// all is fine
			$this->progress( 0 );

			return;
		}

		if ( $row->page_namespace == NS_FILE && $this->fileExists( $row->page_title ) ) {
			$this->output( "file $row->page_title needs cleanup, please run cleanupImages.php.\n" );
			$this->progress( 0 );
		} elseif ( $title === null ) {
			$this->output( "page $row->page_id ($display) is illegal.\n" );
			$this->moveIllegalPage( $row );
			$this->progress( 1 );
		} else {
			$this->output( "page $row->page_id ($display) doesn't match self.\n" );
			$this->moveInconsistentPage( $row, $title );
			$this->progress( 1 );
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	protected function fileExists( $name ) {
		// XXX: Doesn't actually check for file existence, just presence of image record.
		// This is reasonable, since cleanupImages.php only iterates over the image table.
		$dbr = $this->getReplicaDB();
		$row = $dbr->newSelectQueryBuilder()
			->select( '*' )
			->from( 'image' )
			->where( [ 'img_name' => $name ] )
			->caller( __METHOD__ )
			->fetchRow();

		return $row !== false;
	}

	/**
	 * @param stdClass $row
	 */
	protected function moveIllegalPage( $row ) {
		$legalChars = Title::legalChars();
		$legalizedUnprefixed = preg_replace_callback( "/([^$legalChars])/",
			$this->hexChar( ... ),
			$row->page_title );
		if ( $legalizedUnprefixed == '.' ) {
			$legalizedUnprefixed = '(dot)';
		}
		if ( $legalizedUnprefixed == '_' ) {
			$legalizedUnprefixed = '(space)';
		}
		$ns = (int)$row->page_namespace;

		$title = null;
		// Try to move "Talk:Project:Foo" -> "Project talk:Foo"
		if ( $ns === 1 ) {
			$subjectTitle = Title::newFromText( $legalizedUnprefixed );
			if ( $subjectTitle && !$subjectTitle->isTalkPage() ) {
				$talkTitle = $subjectTitle->getTalkPageIfDefined();
				if ( $talkTitle !== null && !$talkTitle->exists() ) {
					$ns = $talkTitle->getNamespace();
					$title = $talkTitle;
				}
			}
		}

		if ( $title === null ) {
			// Not a talk page or that didn't work
			// move any other broken pages to the main namespace so they can be found together
			if ( $ns !== 0 ) {
				$namespaceInfo = $this->getServiceContainer()->getNamespaceInfo();
				$namespaceName = $namespaceInfo->getCanonicalName( $ns );
				if ( $namespaceName === false ) {
					$namespaceName = "NS$ns"; // Fallback for unknown namespaces
				}
				$ns = 0;
				$legalizedUnprefixed = "$namespaceName:$legalizedUnprefixed";
			}
			$title = Title::newFromText( $this->prefix . $legalizedUnprefixed );
		}

		if ( $title === null ) {
			// It's still not a valid title, try again with a much smaller
			// allowed character set. This will mangle any titles with non-ASCII
			// characters, but if we don't do this the result will be
			// falling back to the Broken/id:foo failsafe below which is worse
			$legalizedUnprefixed = preg_replace_callback( '!([^A-Za-z0-9_:\\-])!',
				$this->hexChar( ... ),
				$legalizedUnprefixed
			);
			$title = Title::newFromText( $this->prefix . $legalizedUnprefixed );
		}

		if ( $title === null ) {
			// Oh well, we tried
			$clean = $this->prefix . 'id:' . $row->page_id;
			$legalized = $this->prefix . $legalizedUnprefixed;
			$this->output( "Couldn't legalize; form '$legalized' still invalid; using '$clean'\n" );
			$title = Title::newFromText( $clean );
		} elseif ( $title->exists( IDBAccessObject::READ_LATEST ) ) {
			$clean = $this->prefix . 'id:' . $row->page_id;
			$conflict = $title->getDBKey();
			$this->output( "Legalized for '$conflict' exists; using '$clean'\n" );
			$title = Title::newFromText( $clean );
		}

		if ( !$title || $title->exists( IDBAccessObject::READ_LATEST ) ) {
			// This can happen in corner cases like if numbers are made not valid
			// title characters using the (deprecated) $wgLegalTitleChars or
			// a 'Broken/id:foo' title already exists
			$this->error( "Destination page {$title->getText()} is invalid or already exists, skipping." );
			return;
		}

		$dest = $title->getDBkey();
		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
			$this->getPrimaryDB()
				->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_title' => $dest, 'page_namespace' => $ns ] )
				->where( [ 'page_id' => $row->page_id ] )
				->caller( __METHOD__ )->execute();
		}
	}

	/**
	 * @param stdClass $row
	 * @param Title $title
	 */
	protected function moveInconsistentPage( $row, Title $title ) {
		$titleImpossible = $title->getInterwiki() || !$title->canExist();
		if ( $title->exists( IDBAccessObject::READ_LATEST ) || $titleImpossible ) {
			if ( $titleImpossible ) {
				$prior = $title->getPrefixedDBkey();
			} else {
				$prior = $title->getDBkey();
			}

			$ns = (int)$row->page_namespace;
			# If a page is saved in the main namespace with a namespace prefix then try to move it into
			# that namespace. If there's no conflict then it will succeed. Otherwise it will hit the condition
			# } else if ($ns !== 0) { and be moved to Broken/Namespace:Title
			# whereas without this check it would just go to Broken/Title
			if ( $ns === 0 ) {
				$ns = $title->getNamespace();
			}

			# Old cleanupTitles could move articles there. See T25147.
			# or a page could be stored as (0, "Special:Foo") in which case the $titleImpossible
			# condition would be true and we've already added a prefix so pretend we're in mainspace
			# and don't add another
			if ( $ns < 0 ) {
				$ns = 0;
			}

			# Namespace which no longer exists. Put the page in the main namespace
			# since we don't have any idea of the old namespace name. See T70501.
			# We build the new title ourself rather than relying on getDBKey() because
			# that will return Special:BadTitle
			$namespaceInfo = $this->getServiceContainer()->getNamespaceInfo();
			if ( !$namespaceInfo->exists( $ns ) ) {
				$clean = "{$this->prefix}NS$ns:$row->page_title";
				$ns = 0;
			} elseif ( !$titleImpossible && !$title->exists( IDBAccessObject::READ_LATEST ) ) {
				// Looks like the current title, after cleaning it up, is valid and available
				$clean = $prior;
			} elseif ( $ns !== 0 ) {
				// Put all broken pages in the main namespace so that they can be found via Special:PrefixIndex
				$nsName = $namespaceInfo->getCanonicalName( $ns );
				$clean = "{$this->prefix}$nsName:{$prior}";
				$ns = 0;
			} else {
				$clean = $this->prefix . $prior;
			}
			$verified = Title::makeTitleSafe( $ns, $clean );
			if ( !$verified || $verified->exists( IDBAccessObject::READ_LATEST ) ) {
				$lastResort = "{$this->prefix}id: {$row->page_id}";
				$this->output( "Couldn't legalize; form '$clean' exists; using '$lastResort'\n" );
				$verified = Title::makeTitleSafe( $ns, $lastResort );
				if ( !$verified || $verified->exists( IDBAccessObject::READ_LATEST ) ) {
					// This can happen in corner cases like if numbers are made not valid
					// title characters using the (deprecated) $wgLegalTitleChars or
					// a 'Broken/id:foo' title already exists
					$this->error( "Destination page $lastResort invalid or already exists." );
					return;
				}
			}
			$title = $verified;
		}

		$ns = $title->getNamespace();
		$dest = $title->getDBkey();

		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would rename $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
		} else {
			$this->output( "renaming $row->page_id ($row->page_namespace," .
				"'$row->page_title') to ($ns,'$dest')\n" );
			$this->getPrimaryDB()
				->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [
					'page_namespace' => $ns,
					'page_title' => $dest
				] )
				->where( [ 'page_id' => $row->page_id ] )
				->caller( __METHOD__ )->execute();
			$this->getServiceContainer()->getLinkCache()->clear();
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = TitleCleanup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
