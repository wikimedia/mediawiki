<?php
/**
 * Ensure all files in a given directory match content of the wiki
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
 *
 */
class ImportPagesFromFiles extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Ensure all files in a given directory match content of the wiki";
		$this->addArg( 'dir', 'Directory with files', true );
	}

	public function execute() {
		global $wgUser;

		$summary = 'import from files';
		$dir = realpath( $this->getArg() );
		$wgUser = User::newFromName( 'Import script' );
		$exit = 0;

		if ( !file_exists( $dir ) ) {
			$this->error( "Directory '$dir' does not exist\n", true );
		}
		$this->output( "Adding all pages from $dir\n" );
		foreach ( new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $dir ) ) as $x ) {
			if ( !$x->isFile() || substr( $x->getFilename(), 0, 1 ) === '_' ) {
				continue;
			}
			$fullPath = $x->getPathname();
			if ( strpos( $fullPath, $dir ) !== 0 ) {
				$this->error( "Dir '$dir' not found in '$fullPath'", true );
			}
			// Remove container directory from the path and optional first '/'
			$textTitle = ltrim( substr( $fullPath, strlen( $dir ) ), DIRECTORY_SEPARATOR );
			$textTitle = urldecode( str_replace( DIRECTORY_SEPARATOR, ':', $textTitle ) );
			$title = Title::newFromText( $textTitle );
			if ( !$title ) {
				$this->error( "Invalid title", true );
			}

			// For files, if the name ends with '_', treat it as wikipage, not content
			if ( $title->inNamespace( NS_FILE ) && substr( $textTitle, -1 ) !== '_' ) {
				$this->output( "{$title}..." );
				$file = wfLocalFile( $title );
				$repo = $file->getRepo();
				$dupes = $repo->findBySha1( FSFile::getSha1Base36FromPath( $fullPath ) );
				if ( $dupes ) {
					$this->output( "already exists as " . $dupes[0]->getName() . ", skipping\n" );
					continue;
				}
				$props = FSFile::getPropsFromPath( $fullPath );
				$publishOptions = array();
				$handler = MediaHandler::getHandler( $props['mime'] );
				if ( $handler ) {
					$publishOptions['headers'] = $handler->getStreamHeaders( $props['metadata'] );
				} else {
					$publishOptions['headers'] = array();
				}
				$archive = $file->publish( $fullPath, 0, $publishOptions );
				if ( !$archive->isGood() ) {
					$this->output( "failed. (" . $archive->getWikiText() . ")\n" );
					$exit = 1;
				}
				$textFile = $fullPath . '_';
				$text = is_file( $textFile ) ? file_get_contents( $textFile ) : '';
				if ( $file->recordUpload2( $archive->value, $summary, $text, $props ) ) {
					$this->output( "done.\n" );
				} else {
					$this->output( "recordUpload2() failed.\n" );
				}
			} else {
				$this->output( "Article {$title}..." );
				$page = WikiPage::factory( $title );
				$text = file_get_contents( $fullPath );
				$content = ContentHandler::makeContent( $text, $title );

				$status = $page->doEditContent( $content, $summary );
				if ( $status->isOK() ) {
					$this->output( "done" );
					$exit = 0;
				} else {
					$this->output( "failed" );
					$exit = 1;
				}
				if ( !$status->isGood() ) {
					$this->output( ', ' . $status->getWikiText() );
				}
				$this->output( "\n" );
			}
		}

		exit( $exit );
	}
}

$maintClass = "ImportPagesFromFiles";
require_once RUN_MAINTENANCE_IF_MAIN;
