<?php
/**
 * Clean up broken, unparseable upload filenames.
 *
 * Copyright © 2005-2006 Brion Vibber <brion@pobox.com>
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
 * @author Brion Vibber <brion at pobox.com>
 * @ingroup Maintenance
 */

require_once __DIR__ . '/cleanupTable.inc';

/**
 * Maintenance script to clean up broken, unparseable upload filenames.
 *
 * @ingroup Maintenance
 */
class ImageCleanup extends TableCleanup {
	protected $defaultParams = [
		'table' => 'image',
		'conds' => [],
		'index' => 'img_name',
		'callback' => 'processRow',
	];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to clean up broken, unparseable upload filenames' );
	}

	protected function processRow( $row ) {
		global $wgContLang;

		$source = $row->img_name;
		if ( $source == '' ) {
			// Ye olde empty rows. Just kill them.
			$this->killRow( $source );

			return $this->progress( 1 );
		}

		$cleaned = $source;

		// About half of old bad image names have percent-codes
		$cleaned = rawurldecode( $cleaned );

		// We also have some HTML entities there
		$cleaned = Sanitizer::decodeCharReferences( $cleaned );

		// Some are old latin-1
		$cleaned = $wgContLang->checkTitleEncoding( $cleaned );

		// Many of remainder look like non-normalized unicode
		$cleaned = $wgContLang->normalize( $cleaned );

		$title = Title::makeTitleSafe( NS_FILE, $cleaned );

		if ( is_null( $title ) ) {
			$this->output( "page $source ($cleaned) is illegal.\n" );
			$safe = $this->buildSafeTitle( $cleaned );
			if ( $safe === false ) {
				return $this->progress( 0 );
			}
			$this->pokeFile( $source, $safe );

			return $this->progress( 1 );
		}

		if ( $title->getDBkey() !== $source ) {
			$munged = $title->getDBkey();
			$this->output( "page $source ($munged) doesn't match self.\n" );
			$this->pokeFile( $source, $munged );

			return $this->progress( 1 );
		}

		return $this->progress( 0 );
	}

	/**
	 * @param string $name
	 */
	private function killRow( $name ) {
		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would delete bogus row '$name'\n" );
		} else {
			$this->output( "deleting bogus row '$name'\n" );
			$db = $this->getDB( DB_MASTER );
			$db->delete( 'image',
				[ 'img_name' => $name ],
				__METHOD__ );
		}
	}

	private function filePath( $name ) {
		if ( !isset( $this->repo ) ) {
			$this->repo = RepoGroup::singleton()->getLocalRepo();
		}

		return $this->repo->getRootDirectory() . '/' . $this->repo->getHashPath( $name ) . $name;
	}

	private function imageExists( $name, $db ) {
		return $db->selectField( 'image', '1', [ 'img_name' => $name ], __METHOD__ );
	}

	private function pageExists( $name, $db ) {
		return $db->selectField(
			'page',
			'1',
			[ 'page_namespace' => NS_FILE, 'page_title' => $name ],
			__METHOD__
		);
	}

	private function pokeFile( $orig, $new ) {
		$path = $this->filePath( $orig );
		if ( !file_exists( $path ) ) {
			$this->output( "missing file: $path\n" );
			$this->killRow( $orig );

			return;
		}

		$db = $this->getDB( DB_MASTER );

		/*
		 * To prevent key collisions in the update() statements below,
		 * if the target title exists in the image table, or if both the
		 * original and target titles exist in the page table, append
		 * increasing version numbers until the target title exists in
		 * neither.  (See also T18916.)
		 */
		$version = 0;
		$final = $new;
		$conflict = ( $this->imageExists( $final, $db ) ||
			( $this->pageExists( $orig, $db ) && $this->pageExists( $final, $db ) ) );

		while ( $conflict ) {
			$this->output( "Rename conflicts with '$final'...\n" );
			$version++;
			$final = $this->appendTitle( $new, "_$version" );
			$conflict = ( $this->imageExists( $final, $db ) || $this->pageExists( $final, $db ) );
		}

		$finalPath = $this->filePath( $final );

		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would rename $path to $finalPath\n" );
		} else {
			$this->output( "renaming $path to $finalPath\n" );
			// @todo FIXME: Should this use File::move()?
			$this->beginTransaction( $db, __METHOD__ );
			$db->update( 'image',
				[ 'img_name' => $final ],
				[ 'img_name' => $orig ],
				__METHOD__ );
			$db->update( 'oldimage',
				[ 'oi_name' => $final ],
				[ 'oi_name' => $orig ],
				__METHOD__ );
			$db->update( 'page',
				[ 'page_title' => $final ],
				[ 'page_title' => $orig, 'page_namespace' => NS_FILE ],
				__METHOD__ );
			$dir = dirname( $finalPath );
			if ( !file_exists( $dir ) ) {
				if ( !wfMkdirParents( $dir, null, __METHOD__ ) ) {
					$this->output( "RENAME FAILED, COULD NOT CREATE $dir" );
					$this->rollbackTransaction( $db, __METHOD__ );

					return;
				}
			}
			if ( rename( $path, $finalPath ) ) {
				$this->commitTransaction( $db, __METHOD__ );
			} else {
				$this->error( "RENAME FAILED" );
				$this->rollbackTransaction( $db, __METHOD__ );
			}
		}
	}

	private function appendTitle( $name, $suffix ) {
		return preg_replace( '/^(.*)(\..*?)$/',
			"\\1$suffix\\2", $name );
	}

	private function buildSafeTitle( $name ) {
		$x = preg_replace_callback(
			'/([^' . Title::legalChars() . ']|~)/',
			[ $this, 'hexChar' ],
			$name );

		$test = Title::makeTitleSafe( NS_FILE, $x );
		if ( is_null( $test ) || $test->getDBkey() !== $x ) {
			$this->error( "Unable to generate safe title from '$name', got '$x'" );

			return false;
		}

		return $x;
	}
}

$maintClass = ImageCleanup::class;
require_once RUN_MAINTENANCE_IF_MAIN;
