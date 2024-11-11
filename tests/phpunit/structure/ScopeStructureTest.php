<?php

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;

/**
 * @coversNothing
 */
class ScopeStructureTest extends MediaWikiIntegrationTestCase {

	public static function provideAutoloadNoFileScope() {
		global $wgAutoloadLocalClasses;
		$files = array_unique( $wgAutoloadLocalClasses );
		$args = [];
		foreach ( $files as $file ) {
			$args[$file] = [ $file ];
		}
		return $args;
	}

	/**
	 * Confirm that all files in $wgAutoloadLocalClasses have no file-scope code
	 * apart from specific exemptions.
	 *
	 * This is slow (~15s).
	 *
	 * @dataProvider provideAutoloadNoFileScope
	 */
	public function testAutoloadNoFileScope( $file ) {
		$parser = ( new ParserFactory )
			->createForVersion( PhpVersion::fromComponents( 7, 0 ) );
		$ast = $parser->parse( file_get_contents( $file ) );
		foreach ( $ast as $node ) {
			if ( $node instanceof Stmt\ClassLike
				|| $node instanceof Stmt\Namespace_
				|| $node instanceof Stmt\Use_
				|| $node instanceof Stmt\Nop
				|| $node instanceof Stmt\Declare_
				|| $node instanceof Stmt\Function_
			) {
				continue;
			}
			if ( $node instanceof Stmt\Expression ) {
				$expr = $node->expr;
				if ( $expr instanceof Expr\FuncCall ) {
					if ( $expr->name instanceof Node\Name ) {
						if ( in_array( $expr->name->toString(), [
							'class_alias',
							'define'
						] ) ) {
							continue;
						}
					}
				} elseif ( $expr instanceof Expr\Include_ ) {
					if ( $expr->type === Expr\Include_::TYPE_REQUIRE_ONCE ) {
						continue;
					}
				} elseif ( $expr instanceof Expr\Assign ) {
					if ( $expr->var->name === 'maintClass' ) {
						continue;
					}
				}
			}
			$line = $node->getLine();
			$this->assertNull( $node, "Found file scope code in $file at line $line" );
		}
		$this->assertTrue( true );
	}
}
