<?php
/**
 * Clean up broken, unparseable upload filenames.
 *
 * Copyright © 2005-2006 Brooke Vibber <bvibber@wikimedia.org>
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

use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\IReadableDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/TableCleanup.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to clean up broken, unparseable upload filenames.
 *
 * @ingroup Maintenance
 */
class CleanupImages extends TableCleanup {
	/** @inheritDoc */
	protected $defaultParams = [
		'table' => 'image',
		'conds' => [],
		'index' => 'img_name',
		'callback' => 'processRow',
	];

	/** @var LocalRepo|null */
	private $repo;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to clean up broken, unparseable upload filenames' );
	}

	protected function processRow( \stdClass $row ) {
		$source = $row->img_name;
		if ( $source == '' ) {
			// Ye olde empty rows. Just kill them.
			$this->killRow( $source );

			$this->progress( 1 );
			return;
		}

		$cleaned = $source;

		// About half of old bad image names have percent-codes
		$cleaned = rawurldecode( $cleaned );

		// We also have some HTML entities there
		$cleaned = Sanitizer::decodeCharReferences( $cleaned );

		$contLang = $this->getServiceContainer()->getContentLanguage();

		// Some are old latin-1
		$cleaned = $contLang->checkTitleEncoding( $cleaned );

		// Many of remainder look like non-normalized unicode
		$cleaned = $contLang->normalize( $cleaned );

		$title = Title::makeTitleSafe( NS_FILE, $cleaned );

		if ( $title === null ) {
			$this->output( "page $source ($cleaned) is illegal.\n" );
			$safe = $this->buildSafeTitle( $cleaned );
			if ( $safe === false ) {
				$this->progress( 0 );
				return;
			}
			$this->pokeFile( $source, $safe );

			$this->progress( 1 );
			return;
		}

		if ( $title->getDBkey() !== $source ) {
			$munged = $title->getDBkey();
			$this->output( "page $source ($munged) doesn't match self.\n" );
			$this->pokeFile( $source, $munged );

			$this->progress( 1 );
			return;
		}

		$this->progress( 0 );
	}

	/**
	 * @param string $name
	 */
	private function killRow( $name ) {
		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would delete bogus row '$name'\n" );
		} else {
			$this->output( "deleting bogus row '$name'\n" );
			$db = $this->getPrimaryDB();
			$db->newDeleteQueryBuilder()
				->deleteFrom( 'image' )
				->where( [ 'img_name' => $name ] )
				->caller( __METHOD__ )
				->execute();
		}
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function filePath( $name ) {
		if ( $this->repo === null ) {
			$this->repo = $this->getServiceContainer()->getRepoGroup()->getLocalRepo();
		}

		return $this->repo->getRootDirectory() . '/' . $this->repo->getHashPath( $name ) . $name;
	}

	private function imageExists( string $name, IReadableDatabase $db ): bool {
		return (bool)$db->newSelectQueryBuilder()
			->select( '1' )
			->from( 'image' )
			->where( [ 'img_name' => $name ] )
			->caller( __METHOD__ )
			->fetchField();
	}

	private function pageExists( string $name, IReadableDatabase $db ): bool {
		return (bool)$db->newSelectQueryBuilder()
			->select( '1' )
			->from( 'page' )
			->where( [
				'page_namespace' => NS_FILE,
				'page_title' => $name,
			] )
			->caller( __METHOD__ )
			->fetchField();
	}

	private function pokeFile( string $orig, string $new ) {
		$path = $this->filePath( $orig );
		if ( !file_exists( $path ) ) {
			$this->output( "missing file: $path\n" );
			$this->killRow( $orig );

			return;
		}

		$db = $this->getPrimaryDB();

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
			$db->newUpdateQueryBuilder()
				->update( 'image' )
				->set( [ 'img_name' => $final ] )
				->where( [ 'img_name' => $orig ] )
				->caller( __METHOD__ )
				->execute();
			$db->newUpdateQueryBuilder()
				->update( 'oldimage' )
				->set( [ 'oi_name' => $final ] )
				->where( [ 'oi_name' => $orig ] )
				->caller( __METHOD__ )
				->execute();
			$db->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_title' => $final ] )
				->where( [ 'page_title' => $orig, 'page_namespace' => NS_FILE ] )
				->caller( __METHOD__ )
				->execute();
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

	private function appendTitle( string $name, string $suffix ): string {
		return preg_replace( '/^(.*)(\..*?)$/',
			"\\1$suffix\\2", $name );
	}

	/** @return string|false */
	private function buildSafeTitle( string $name ) {
		$x = preg_replace_callback(
			'/([^' . Title::legalChars() . ']|~)/',
			[ $this, 'hexChar' ],
			$name );

		$test = Title::makeTitleSafe( NS_FILE, $x );
		if ( $test === null || $test->getDBkey() !== $x ) {
			$this->error( "Unable to generate safe title from '$name', got '$x'" );

			return false;
		}

		return $x;
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupImages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
