<?php
/**
 * Maintenance script that recursively scans MediaWiki's PHP source tree
 * for deprecated functions and methods and pretty-prints the results.
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
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * A PHPParser node visitor that associates each node with its file name.
 */
class FileAwareNodeVisitor extends PhpParser\NodeVisitorAbstract {
	private $currentFile = null;

	public function enterNode( PhpParser\Node $node ) {
		$retVal = parent::enterNode( $node );
		$node->filename = $this->currentFile;
		return $retVal;
	}

	public function setCurrentFile( $filename ) {
		$this->currentFile = $filename;
	}

	public function getCurrentFile() {
		return $this->currentFile;
	}
}

/**
 * A PHPParser node visitor that finds deprecated functions and methods.
 */
class DeprecatedInterfaceFinder extends FileAwareNodeVisitor {

	private $currentClass = null;

	private $foundNodes = [];

	public function getFoundNodes() {
		// Sort results by version, then by filename, then by name.
		foreach ( $this->foundNodes as $version => &$nodes ) {
			uasort( $nodes, function ( $a, $b ) {
				return ( $a['filename'] . $a['name'] ) < ( $b['filename'] . $b['name'] ) ? -1 : 1;
			} );
		}
		ksort( $this->foundNodes );
		return $this->foundNodes;
	}

	/**
	 * Check whether a function or method includes a call to wfDeprecated(),
	 * indicating that it is a hard-deprecated interface.
	 */
	public function isHardDeprecated( PhpParser\Node $node ) {
		if ( !$node->stmts ) {
			return false;
		}
		foreach ( $node->stmts as $stmt ) {
			if (
				$stmt instanceof PhpParser\Node\Expr\FuncCall
				&& $stmt->name->toString() === 'wfDeprecated'
			) {
				return true;
			}
			return false;
		}
	}

	public function enterNode( PhpParser\Node $node ) {
		$retVal = parent::enterNode( $node );

		if ( $node instanceof PhpParser\Node\Stmt\ClassLike ) {
			$this->currentClass = $node->name;
		}

		if ( $node instanceof PhpParser\Node\FunctionLike ) {
			$docComment = $node->getDocComment();
			if ( !$docComment ) {
				return;
			}
			if ( !preg_match( '/@deprecated.*(\d+\.\d+)/', $docComment->getText(), $matches ) ) {
				return;
			}
			$version = $matches[1];

			if ( $node instanceof PhpParser\Node\Stmt\ClassMethod ) {
				$name = $this->currentClass . '::' . $node->name;
			} else {
				$name = $node->name;
			}

			$this->foundNodes[ $version ][] = [
				'filename' => $node->filename,
				'line'     => $node->getLine(),
				'name'     => $name,
				'hard'     => $this->isHardDeprecated( $node ),
			];
		}

		return $retVal;
	}
}

/**
 * Maintenance task that recursively scans MediaWiki PHP files for deprecated
 * functions and interfaces and produces a report.
 */
class FindDeprecated extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Find deprecated interfaces' );
	}

	public function getFiles() {
		global $IP;

		$files = new RecursiveDirectoryIterator( $IP . '/includes' );
		$files = new RecursiveIteratorIterator( $files );
		$files = new RegexIterator( $files, '/\.php$/' );
		return iterator_to_array( $files, false );
	}

	public function execute() {
		global $IP;

		$files = $this->getFiles();
		$chunkSize = ceil( count( $files ) / 72 );

		$parser = ( new PhpParser\ParserFactory )->create( PhpParser\ParserFactory::PREFER_PHP7 );
		$traverser = new PhpParser\NodeTraverser;
		$finder = new DeprecatedInterfaceFinder;
		$traverser->addVisitor( $finder );

		$fileCount = count( $files );

		for ( $i = 0; $i < $fileCount; $i++ ) {
			$file = $files[$i];
			$code = file_get_contents( $file );

			if ( strpos( $code, '@deprecated' ) === -1 ) {
				continue;
			}

			$finder->setCurrentFile( substr( $file->getPathname(), strlen( $IP ) + 1 ) );
			$nodes = $parser->parse( $code, [ 'throwOnError' => false ] );
			$traverser->traverse( $nodes );

			if ( $i % $chunkSize === 0 ) {
				$percentDone = 100 * $i / $fileCount;
				fprintf( STDERR, "\r[%-72s] %d%%", str_repeat( '#', $i / $chunkSize ), $percentDone );
			}
		}

		fprintf( STDERR, "\r[%'#-72s] 100%%\n", '' );

		// Colorize output if STDOUT is an interactive terminal.
		if ( posix_isatty( STDOUT ) ) {
			$versionFmt = "\n* Deprecated since \033[37;1m%s\033[0m:\n";
			$entryFmt = "  %s \033[33;1m%s\033[0m (%s:%d)\n";
		} else {
			$versionFmt = "\n* Deprecated since %s:\n";
			$entryFmt = "  %s %s (%s:%d)\n";
		}

		foreach ( $finder->getFoundNodes() as $version => $nodes ) {
			printf( $versionFmt, $version );
			foreach ( $nodes as $node ) {
				printf(
					$entryFmt,
					$node['hard'] ? '+' : '-',
					$node['name'],
					$node['filename'],
					$node['line']
				);
			}
		}
		printf( "\nlegend:\n -: soft-deprecated\n +: hard-deprecated (via wfDeprecated())\n" );
	}
}

$maintClass = 'FindDeprecated';
require_once RUN_MAINTENANCE_IF_MAIN;
