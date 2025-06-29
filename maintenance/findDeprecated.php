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
 * @phan-file-suppress PhanUndeclaredProperty Lots of custom properties
 */

use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
require_once __DIR__ . '/../vendor/autoload.php';
// @codeCoverageIgnoreEnd

/**
 * A PHPParser node visitor that associates each node with its file name.
 */
class FileAwareNodeVisitor extends PhpParser\NodeVisitorAbstract {
	/** @var string|null */
	private $currentFile = null;

	public function enterNode( PhpParser\Node $node ) {
		$retVal = parent::enterNode( $node );
		$node->filename = $this->currentFile;
		return $retVal;
	}

	public function setCurrentFile( ?string $filename ) {
		$this->currentFile = $filename;
	}

	public function getCurrentFile(): ?string {
		return $this->currentFile;
	}
}

/**
 * A PHPParser node visitor that finds deprecated functions and methods.
 */
class DeprecatedInterfaceFinder extends FileAwareNodeVisitor {

	/** @var string */
	private $currentClass = null;

	/** @var array[] */
	private $foundNodes = [];

	public function getFoundNodes(): array {
		// Sort results by version, then by filename, then by name.
		foreach ( $this->foundNodes as &$nodes ) {
			uasort( $nodes, static function ( $a, $b ) {
				return ( $a['filename'] . $a['name'] ) <=> ( $b['filename'] . $b['name'] );
			} );
		}
		ksort( $this->foundNodes );
		return $this->foundNodes;
	}

	/**
	 * Check whether a function or method includes a call to wfDeprecated(),
	 * indicating that it is a hard-deprecated interface.
	 * @param PhpParser\Node $node
	 * @return bool
	 */
	public function isHardDeprecated( PhpParser\Node $node ) {
		if ( !$node->stmts ) {
			return false;
		}
		foreach ( $node->stmts as $stmt ) {
			$functionExpression = null;
			if ( $stmt instanceof PhpParser\Node\Expr\FuncCall ) {
				$functionExpression = $stmt;
			}
			if ( isset( $stmt->expr ) && $stmt->expr instanceof PhpParser\Node\Expr\FuncCall ) {
				$functionExpression = $stmt->expr;
			}
			if ( $functionExpression && $functionExpression->name->toString() === 'wfDeprecated' ) {
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

	/**
	 * @return string The installation path of MediaWiki. This method is mocked in PHPUnit tests.
	 */
	protected function getMwInstallPath() {
		return MW_INSTALL_PATH;
	}

	/**
	 * @return SplFileInfo[]
	 */
	public function getFiles() {
		$files = new RecursiveDirectoryIterator( $this->getMwInstallPath() . '/includes' );
		$files = new RecursiveIteratorIterator( $files );
		$files = new RegexIterator( $files, '/\.php$/' );
		return iterator_to_array( $files, false );
	}

	public function execute() {
		global $IP;

		$files = $this->getFiles();
		$chunkSize = (int)ceil( count( $files ) / 72 );

		$parser = ( new PhpParser\ParserFactory )->createForVersion( PhpParser\PhpVersion::fromComponents( 7, 0 ) );
		$traverser = new PhpParser\NodeTraverser;
		$finder = new DeprecatedInterfaceFinder;
		$traverser->addVisitor( $finder );

		$fileCount = count( $files );

		$outputProgress = !defined( 'MW_PHPUNIT_TEST' );

		for ( $i = 0; $i < $fileCount; $i++ ) {
			$file = $files[$i];
			$code = file_get_contents( $file );

			if ( !str_contains( $code, '@deprecated' ) ) {
				continue;
			}

			$finder->setCurrentFile( substr( $file->getPathname(), strlen( $IP ) + 1 ) );
			$nodes = $parser->parse( $code );
			$traverser->traverse( $nodes );

			if ( $i % $chunkSize === 0 ) {
				$percentDone = 100 * $i / $fileCount;
				if ( $outputProgress ) {
					fprintf( STDERR, "\r[%-72s] %d%%", str_repeat( '#', $i / $chunkSize ), $percentDone );
				}
			}
		}

		if ( $outputProgress ) {
			fprintf( STDERR, "\r[%'#-72s] 100%%\n", '' );
		}

		foreach ( $finder->getFoundNodes() as $version => $nodes ) {
			echo "\n* Deprecated since $version:\n";
			foreach ( $nodes as $node ) {
				printf(
					"  %s %s (%s:%d)\n",
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

// @codeCoverageIgnoreStart
$maintClass = FindDeprecated::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
