<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Message;

use MediaWiki\Message\Message;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWiki\Message\TextFormatter;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Message\MessageFormatterFactory
 */
class MessageFormatterFactoryTest extends MediaWikiIntegrationTestCase {

	public function testGetTextFormatter() {
		$factoryMock = new MessageFormatterFactory( Message::FORMAT_TEXT );
		$langCodeMock = 'en';
		$formatterMock = $factoryMock->getTextFormatter( $langCodeMock );
		$this->assertInstanceOf( TextFormatter::class, $formatterMock );
		$this->assertSame( $langCodeMock, $formatterMock->getLangCode() );
	}

	public function testGetTextFormatterReturnsSameInstanceForSameLangCode() {
		$factoryMock = new MessageFormatterFactory();
		$langCodeMock = 'en';
		$formatterMock1 = $factoryMock->getTextFormatter( $langCodeMock );
		$formatterMock2 = $factoryMock->getTextFormatter( $langCodeMock );
		$this->assertSame( $formatterMock1, $formatterMock2 );
	}

	public function testGetTextFormatterReturnsDifferentInstancesForDifferentLangCodes() {
		$factoryMock = new MessageFormatterFactory( Message::FORMAT_PLAIN );
		$langCodeMock1 = 'en';
		$langCodeMock2 = 'fr';
		$formatterMock1 = $factoryMock->getTextFormatter( $langCodeMock1 );
		$formatterMock2 = $factoryMock->getTextFormatter( $langCodeMock2 );
		$this->assertNotSame( $formatterMock1, $formatterMock2 );
	}

	public function testGetTextFormatterWithDifferentFormats() {
		$factoryMock1 = new MessageFormatterFactory( Message::FORMAT_TEXT );
		$factoryMock2 = new MessageFormatterFactory( Message::FORMAT_PLAIN );
		$langCodeMock = 'en';
		$formatterMock1 = $factoryMock1->getTextFormatter( $langCodeMock );
		$formatterMock2 = $factoryMock2->getTextFormatter( $langCodeMock );
		$this->assertNotSame( $formatterMock1, $formatterMock2 );
	}
}
