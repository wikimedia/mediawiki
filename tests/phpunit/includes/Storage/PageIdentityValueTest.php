<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\PageIdentityValue;
use MediaWikiTestCase;
use TitleValue;

/**
 * @covers MediaWiki\Storage\PageIdentityValue
 */
class PageIdentityValueTest extends \PHPUnit_Framework_TestCase {

	public function testNewFromDBKey() {
		$page = PageIdentityValue::newFromDBKey( 17, 23, 'PIV_Test' );

		$this->assertSame( 17, $page->getId(), 'getId()' );
		$this->assertSame( 23, $page->getNamespace(), 'getNamespace()' );
		$this->assertSame( 'PIV_Test', $page->getTitleDBKey(), 'getTitleDBKey()' );

		$target = $page->getAsLinkTarget();
		$this->assertInstanceOf( LinkTarget::class, $target, 'getAsLinkTarget()' );
		$this->assertSame( 23, $target->getNamespace(), 'getAsLinkTarget()->getNamespace()' );
		$this->assertSame( 'PIV_Test', $target->getDBKey(), 'getAsLinkTarget()->getDBKey()' );
	}

	public function testConstructor() {
		$page = new PageIdentityValue( 17, new TitleValue( 23, 'PIV_Test' ) );

		$this->assertSame( 17, $page->getId(), 'getId()' );
		$this->assertSame( 23, $page->getNamespace(), 'getNamespace()' );
		$this->assertSame( 'PIV_Test', $page->getTitleDBKey(), 'getTitleDBKey()' );

		$target = $page->getAsLinkTarget();
		$this->assertInstanceOf( LinkTarget::class, $target, 'getAsLinkTarget()' );
		$this->assertSame( 23, $target->getNamespace(), 'getAsLinkTarget()->getNamespace()' );
		$this->assertSame( 'PIV_Test', $target->getDBKey(), 'getAsLinkTarget()->getDBKey()' );
	}

	public function testExists() {
		$page = new PageIdentityValue( 17, new TitleValue( 23, 'PIV_Test' ) );
		$this->assertTrue( $page->exists(), 'exists()' );

		$page = new PageIdentityValue( 0, new TitleValue( 23, 'PIV_Test' ) );
		$this->assertFalse( $page->exists(), 'exists()' );
	}

	public function testGetTitleDBKey() {
		$page = new PageIdentityValue( 17, new TitleValue( 23, 'PIV_Test' ) );

		$this->assertSame( 'PIV_Test', $page->getTitleDBKey(), 'getTitleDBKey()' );
	}

	public function testGetTitleText() {
		$page = new PageIdentityValue( 17, new TitleValue( 23, 'PIV_Test' ) );

		$this->assertSame( 'PIV Test', $page->getTitleText(), 'getTitleText()' );
	}

	public function testInNamespace() {
		$page = new PageIdentityValue( 17, new TitleValue( 23, 'PIV_Test' ) );

		$this->assertTrue( $page->inNamespace( 23 ) );
		$this->assertFalse( $page->inNamespace( 44 ) );
	}

}
