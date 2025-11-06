<?php
namespace MediaWiki\Tests\Specials\Pager;

use MediaWiki\Context\RequestContext;
use MediaWiki\Pager\ImageListPager;
use MediaWikiIntegrationTestCase;
use UnexpectedValueException;

/**
 * Test class for ImageListPagerTest class.
 *
 * Copyright © 2013, Antoine Musso
 * Copyright © 2013, Siebrand Mazeland
 * Copyright © 2013, Wikimedia Foundation Inc.
 *
 * @group Database
 */
class ImageListPagerTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \MediaWiki\Pager\ImageListPager::formatValue
	 */
	public function testFormatValuesThrowException() {
		$services = $this->getServiceContainer();
		$page = new ImageListPager(
			RequestContext::getMain(),
			$services->getCommentStore(),
			$services->getLinkRenderer(),
			$services->getConnectionProvider(),
			$services->getRepoGroup(),
			$services->getUserNameUtils(),
			$services->getRowCommentFormatter(),
			$services->getLinkBatchFactory(),
			null,
			'',
			false,
			false
		);
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( "invalid_field" );
		$page->formatValue( 'invalid_field', 'invalid_value' );
	}

	/**
	 * @covers \MediaWiki\Pager\ImageListPager::formatValue
	 */
	public function testFormatValues_img_timestamp() {
		$services = $this->getServiceContainer();
		$this->setUserLang( 'en' );
		$page = new ImageListPager(
			RequestContext::getMain(),
			$services->getCommentStore(),
			$services->getLinkRenderer(),
			$services->getConnectionProvider(),
			$services->getRepoGroup(),
			$services->getUserNameUtils(),
			$services->getRowCommentFormatter(),
			$services->getLinkBatchFactory(),
			null,
			'',
			false,
			false
		);
		$this->assertEquals( '12:34, 15 January 2001', $page->formatValue( 'img_timestamp', '20010115123456' ) );
	}

	/**
	 * @covers \MediaWiki\Pager\ImageListPager::formatValue
	 */
	public function testFormatValues_img_size() {
		$services = $this->getServiceContainer();
		$this->setUserLang( 'en' );
		$page = new ImageListPager(
			RequestContext::getMain(),
			$services->getCommentStore(),
			$services->getLinkRenderer(),
			$services->getConnectionProvider(),
			$services->getRepoGroup(),
			$services->getUserNameUtils(),
			$services->getRowCommentFormatter(),
			$services->getLinkBatchFactory(),
			null,
			'',
			false,
			false
		);

		// For very small values we specify exactly
		$this->assertEquals( '10 bytes', $page->formatValue( 'img_size', '10' ) );
		$this->assertEquals( '16 bytes', $page->formatValue( 'img_size', '16' ) );

		// Above 999 but below 1024 we report bytes with thousands separator
		$this->assertEquals( '1,000 bytes', $page->formatValue( 'img_size', '1000' ) );
		$this->assertEquals( '1,023 bytes', $page->formatValue( 'img_size', '1023' ) );

		// For kilobytes we round to the nearest
		$this->assertEquals( '1 KB', $page->formatValue( 'img_size', '1100' ) );
		$this->assertEquals( '2 KB', $page->formatValue( 'img_size', '1600' ) );

		// For megabytes and above we specify up to 2 decimal places if appropriate
		$this->assertEquals( '1 MB', $page->formatValue( 'img_size', '1048576' ) );
		$this->assertEquals( '1.05 MB', $page->formatValue( 'img_size', '1100000' ) );
		$this->assertEquals( '1.53 MB', $page->formatValue( 'img_size', '1600000' ) );

		$this->assertEquals( '1 GB', $page->formatValue( 'img_size', '1073741824' ) );
		$this->assertEquals( '1.02 GB', $page->formatValue( 'img_size', '1100000000' ) );
		$this->assertEquals( '1.49 GB', $page->formatValue( 'img_size', '1600000000' ) );
	}

	/**
	 * @covers \MediaWiki\Pager\ImageListPager::formatValue
	 */
	public function testFormatValues_count() {
		$services = $this->getServiceContainer();
		$page = new ImageListPager(
			RequestContext::getMain(),
			$services->getCommentStore(),
			$services->getLinkRenderer(),
			$services->getConnectionProvider(),
			$services->getRepoGroup(),
			$services->getUserNameUtils(),
			$services->getRowCommentFormatter(),
			$services->getLinkBatchFactory(),
			null,
			'',
			false,
			false
		);

		// Values are 0-indexed but humans count from 1.
		$this->assertSame( '1', $page->formatValue( 'count', '0' ) );
		$this->assertSame( '2', $page->formatValue( 'count', '1' ) );
		$this->assertSame( '3', $page->formatValue( 'count', '2' ) );
	}
}
