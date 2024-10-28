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
