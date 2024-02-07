<?php
/**
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
 * @since 1.42
 */

namespace MediaWiki\Tests\Unit\CommentFormatter;

use Language;
use LinkCache;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;
use MediaWikiUnitTestCase;
use RepoGroup;

/**
 * @group CommentFormatter
 * @coversDefaultClass \MediaWiki\CommentFormatter\CommentParserFactory
 */
class CommentParserFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * Test the constructor of the CommentParserFactory class.
	 * @covers ::__construct
	 */
	public function testConstruct() {
		// Create a new CommentParserFactory object
		$factory = new CommentParserFactory(
			$this->createMock( LinkRenderer::class ),
			$this->createMock( LinkBatchFactory::class ),
			$this->createMock( LinkCache::class ),
			$this->createMock( RepoGroup::class ),
			$this->createMock( Language::class ),
			$this->createMock( Language::class ),
			$this->createMock( TitleParser::class ),
			$this->createMock( NamespaceInfo::class ),
			$this->createMock( HookContainer::class )
		);

		// Verify the private properties are set to the expected values
		$this->assertObjectHasAttribute( 'linkRenderer', $factory );
		$this->assertObjectHasAttribute( 'linkBatchFactory', $factory );
		$this->assertObjectHasAttribute( 'linkCache', $factory );
		$this->assertObjectHasAttribute( 'repoGroup', $factory );
		$this->assertObjectHasAttribute( 'userLang', $factory );
		$this->assertObjectHasAttribute( 'contLang', $factory );
		$this->assertObjectHasAttribute( 'titleParser', $factory );
		$this->assertObjectHasAttribute( 'namespaceInfo', $factory );
		$this->assertObjectHasAttribute( 'hookContainer', $factory );
	}

	/**
	 * Test the constructor of the CommentParserFactory class.
	 * @covers ::create
	 */
	public function testCreate() {
		// Create a new CommentParserFactory object
		$factory = new CommentParserFactory(
			$this->createMock( LinkRenderer::class ),
			$this->createMock( LinkBatchFactory::class ),
			$this->createMock( LinkCache::class ),
			$this->createMock( RepoGroup::class ),
			$this->createMock( Language::class ),
			$this->createMock( Language::class ),
			$this->createMock( TitleParser::class ),
			$this->createMock( NamespaceInfo::class ),
			$this->createMock( HookContainer::class )
		);
		// Verify the create method returns a CommentParser object
		$this->assertInstanceOf( CommentParser::class, $factory->create() );
	}

}
