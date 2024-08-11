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
 */

namespace MediaWiki\Tests\Unit\CommentFormatter;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;
use MediaWikiUnitTestCase;
use RepoGroup;

/**
 * @group CommentFormatter
 * @covers \MediaWiki\CommentFormatter\CommentParserFactory
 */
class CommentParserFactoryTest extends MediaWikiUnitTestCase {

	public function testCreate() {
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

		$this->assertInstanceOf( CommentParser::class, $factory->create() );
	}
}
