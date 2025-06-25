<?php
declare( strict_types = 1 );
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

namespace MediaWiki\Tests\Parser\Parsoid;

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\Parsoid\Config\DataAccess;
use MediaWiki\Parser\Parsoid\Config\PageConfigFactory;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWikiUnitTestCase;
use Wikimedia\Parsoid\Config\SiteConfig;

/**
 * $group Parsoid
 * @covers \MediaWiki\Parser\Parsoid\ParsoidParserFactory
 */
class ParsoidParserFactoryTest extends MediaWikiUnitTestCase {

	protected SiteConfig $siteConfig;
	protected DataAccess $dataAccess;
	protected PageConfigFactory $pageConfigFactory;
	protected LanguageConverterFactory $languageConverterFactory;
	protected ParserFactory $legacyParserFactory;

	protected function setUp(): void {
		parent::setUp();
		$this->siteConfig = $this->createMock( SiteConfig::class );
		$this->dataAccess = $this->createMock( DataAccess::class );
		$this->pageConfigFactory = $this->createMock( PageConfigFactory::class );
		$this->languageConverterFactory = $this->createMock( LanguageConverterFactory::class );
		$this->legacyParserFactory = $this->createMock( ParserFactory::class );
	}

	public function testCreate() {
		$factory = new ParsoidParserFactory(
			$this->siteConfig,
			$this->dataAccess,
			$this->pageConfigFactory,
			$this->languageConverterFactory,
			$this->legacyParserFactory
		);
		$this->assertInstanceOf( ParsoidParser::class, $factory->create() );
	}
}
