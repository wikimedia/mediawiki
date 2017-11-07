<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\DefaultPreferencesFactory;

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

class DefaultPreferencesFactoryTest extends MediaWikiTestCase {

	public function setUp() {
		
	}

	/**
	 * @covers DefaultPreferencesFactory::getForm()
	 */
	public function testGetForm() {
		global $wgParserConf;
		$this->setMwGlobals( 'wgParser',
			ObjectFactory::constructClassInstance( $wgParserConf['class'], [ $wgParserConf ] )
		);
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$testUser = $this->getTestUser();
		$factory = new DefaultPreferencesFactory(
			$config,
			new Language(),
			AuthManager::singleton(),
			MediaWikiServices::getInstance()->getLinkRenderer()
		);
		$requestContext = new RequestContext();
		$requestContext->setTitle(Title::newFromText('Test page'));
		$form = $factory->getForm($testUser->getUser(), $requestContext);
		$this->assertInstanceOf(PreferencesForm::class, $form);
		$this->assertCount( 5, $form->getPreferenceSections() );
	}

	/**
	 * @covers DefaultPreferencesFactory::getFormDescriptor()
	 */
	public function testGetFormDescriptor() {
		
	}

	/**
	 * @covers DefaultPreferencesFactory::getSaveBlacklist()
	 */
	public function getSaveBlacklist() {
		
	}

	/**
	 * @covers DefaultPreferencesFactory::submitForm()
	 */
	public function submitForm() {
		
	}
}
