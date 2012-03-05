<?php
/**
 * Check for articles to fix after adding/deleting namespaces
 *
 * Copyright (C) 2005-2007 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class NamespaceConflictChecker extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "";
		$this->addOption( 'fix', 'Attempt to automatically fix errors' );
		$this->addOption( 'suffix', "Dupes will be renamed with correct namespace with\n" .
									"\t\t<text> Appended after the article name", false, true );
		$this->addOption( 'prefix', "Do an explicit check for the given title prefix\n" .
									"\t\tappended after the article name", false, true );
	}

	public function execute() {
		global $wgTitle;

		$this->db = wfGetDB( DB_MASTER );
		$wgTitle = Title::newFromText( 'Namespace title conflict cleanup script' );

		$fix = $this->hasOption( 'fix' );
		$suffix = $this->getOption( 'suffix', '' );
		$prefix = $this->getOption( 'prefix', '' );
		$key = intval( $this->getOption( 'key', 0 ) );

		if ( $prefix ) {
			$retval = $this->checkPrefix( $key, $prefix, $fix, $suffix );
		} else {
			$retval = $this->checkAll( $fix, $suffix );
		}
	
		if ( $retval ) {
			$this->output( "\nLooks good!\n" );
		} else {
			$this->output( "\nOh noeees\n" );
		}
	}

	/**
	 * @todo Document
	 * @param $fix Boolean: whether or not to fix broken entries
	 * @param $suffix String: suffix to append to renamed articles
	 */
	private function checkAll( $fix, $suffix = '' ) {
		global $wgContLang, $wgNamespaceAliases, $wgCanonicalNamespaceNames;
		global $wgCapitalLinks;
		
		$spaces = array();
		
		// List interwikis first, so they'll be overridden
		// by any conflicting local namespaces.
		foreach ( $this->getInterwikiList() as $prefix ) {
			$name = $wgContLang->ucfirst( $prefix );
			$spaces[$name] = 0;
		}

		// Now pull in all canonical and alias namespaces...
		foreach ( $wgCanonicalNamespaceNames as $ns => $name ) {
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
		
		ksort( $spaces );
		asort( $spaces );
		
		$ok = true;
		foreach ( $spaces as $name => $ns ) {
			$ok = $this->checkNamespace( $ns, $name, $fix, $suffix ) && $ok;
		}
		return $ok;
	}

	/**
	 * Get the interwiki list
	 *
	 * @todo Needs to respect interwiki cache!
	 * @return Array
	 */
	private function getInterwikiList() {
		$result = $this->db->select( 'interwiki', array( 'iw_prefix' ) );
		$prefixes = array();
		foreach ( $result as $row ) {
			$prefixes[] = $row->iw_prefix;
		}
		return $prefixes;
	}

	/**
	 * @todo Document
	 * @param $ns Integer: a namespace id
	 * @param $name String
	 * @param $fix Boolean: whether to fix broken entries
	 * @param $suffix String: suffix to append to renamed articles
	 */
	private function checkNamespace( $ns, $name, $fix, $suffix = '' ) {
		$conflicts = $this->getConflicts( $ns, $name );
		$count = count( $conflicts );
		if ( $count == 0 ) {
			return true;
		}

		$ok = true;
		foreach ( $conflicts as $row ) {
			$resolvable = $this->reportConflict( $row, $suffix );
			$ok = $ok && $resolvable;
			if ( $fix && ( $resolvable || $suffix != '' ) ) {
				$ok = $this->resolveConflict( $row, $resolvable, $suffix ) && $ok;
			}
		}
		return $ok;
	}
	
	/**
	 * @todo: do this for reals
	 */
	private function checkPrefix( $key, $prefix, $fix, $suffix = '' ) {
		$this->output( "Checking prefix \"$prefix\" vs namespace $key\n" );
		return $this->checkNamespace( $key, $prefix, $fix, $suffix );
	}

	/**
	 * Find pages in mainspace that have a prefix of the new namespace
	 * so we know titles that will need migrating
	 *
	 * @param $ns Integer: namespace id (id for new namespace?)
	 * @param $name String: prefix that is being made a namespace
	 */
	private function getConflicts( $ns, $name ) {
		$page  = 'page';
		$table = $this->db->tableName( $page );

		$prefix     = $this->db->strencode( $name );
		$encNamespace = $this->db->addQuotes( $ns );

		$titleSql = "TRIM(LEADING '$prefix:' FROM {$page}_title)";
		if ( $ns == 0 ) {
			// An interwiki; try an alternate encoding with '-' for ':'
			$titleSql = $this->db->buildConcat( array( "'$prefix-'", $titleSql ) );
		}
                                     
		$sql = "SELECT {$page}_id    AS id,
		               {$page}_title AS oldtitle,
		               $encNamespace + {$page}_namespace AS namespace,
			       $titleSql     AS title,
			       {$page}_namespace AS oldnamespace
		          FROM {$table}
		         WHERE ( {$page}_namespace=0 OR {$page}_namespace=1 )
		           AND {$page}_title " . $this->db->buildLike( $name . ':', $this->db->anyString() );

		$result = $this->db->query( $sql, __METHOD__ );

		$set = array();
		foreach ( $result as $row ) {
			$set[] = $row;
		}
		return $set;
	}

	/**
	 * Report any conflicts we find
	 */
	private function reportConflict( $row, $suffix ) {
		$newTitle = Title::makeTitleSafe( $row->namespace, $row->title );
		if ( is_null( $newTitle ) || !$newTitle->canExist() ) {
			// Title is also an illegal title...
			// For the moment we'll let these slide to cleanupTitles or whoever.
			$this->output( sprintf( "... %d (%d,\"%s\")\n",
				$row->id,
				$row->oldnamespace,
				$row->oldtitle ) );
			$this->output( "...  *** cannot resolve automatically; illegal title ***\n" );
			return false;
		}

		$this->output( sprintf( "... %d (%d,\"%s\") -> (%d,\"%s\") [[%s]]\n",
			$row->id,
			$row->oldnamespace,
			$row->oldtitle,
			$newTitle->getNamespace(),
			$newTitle->getDBkey(),
			$newTitle->getPrefixedText() ) );

		$id = $newTitle->getArticleId();
		if ( $id ) {
			$this->output( "...  *** cannot resolve automatically; page exists with ID $id ***\n" );
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Resolve any conflicts
	 *
	 * @param $row Object: row from the page table to fix
	 * @param $resolvable Boolean
	 * @param $suffix String: suffix to append to the fixed page
	 */
	private function resolveConflict( $row, $resolvable, $suffix ) {
		if ( !$resolvable ) {
			$this->output( "...  *** old title {$row->title}\n" );
			while ( true ) {
				$row->title .= $suffix;
				$this->output( "...  *** new title {$row->title}\n" );
				$title = Title::makeTitleSafe( $row->namespace, $row->title );
				if ( ! $title ) {
					$this->output( "... !!! invalid title\n" );
					return false;
				}
				if ( $id = $title->getArticleId() ) {
					$this->output( "...  *** page exists with ID $id ***\n" );
				} else {
					break;
				}
			}
			$this->output( "...  *** using suffixed form [[" . $title->getPrefixedText() . "]] ***\n" );
		}
		$this->resolveConflictOn( $row, 'page', 'page' );
		return true;
	}

	/**
	 * Resolve a given conflict
	 *
	 * @param $row Object: row from the old broken entry
	 * @param $table String: table to update
	 * @param $prefix String: prefix for column name, like page or ar
	 */
	private function resolveConflictOn( $row, $table, $prefix ) {
		$this->output( "... resolving on $table... " );
		$newTitle = Title::makeTitleSafe( $row->namespace, $row->title );
		$this->db->update( $table,
			array(
				"{$prefix}_namespace" => $newTitle->getNamespace(),
				"{$prefix}_title"     => $newTitle->getDBkey(),
			),
			array(
				// "{$prefix}_namespace" => 0,
				// "{$prefix}_title"     => $row->oldtitle,
				"{$prefix}_id"		 => $row->id,
			),
			__METHOD__ );
		$this->output( "ok.\n" );
		return true;
	}
}

$maintClass = "NamespaceConflictChecker";
require_once( DO_MAINTENANCE );
