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

	/** @var IContextSource */
	protected $context;

	/** @var Config */
	protected $config;

	public function setUp() {
		parent::setUp();
		global $wgParserConf;
		$this->context = new RequestContext();
		$this->context->setTitle( Title::newFromText( self::class ) );
		$this->setMwGlobals( 'wgParser',
			ObjectFactory::constructClassInstance( $wgParserConf['class'], [ $wgParserConf ] )
		);
		$this->config = MediaWikiServices::getInstance()->getMainConfig();
	}

	/**
	 * @covers DefaultPreferencesFactory::getForm()
	 */
	public function testGetForm() {
		$testUser = $this->getTestUser();
		$factory = new DefaultPreferencesFactory(
			$this->config,
			new Language(),
			AuthManager::singleton(),
			MediaWikiServices::getInstance()->getLinkRenderer()
		);
		$form = $factory->getForm( $testUser->getUser(), $this->context );
		$this->assertInstanceOf( PreferencesForm::class, $form );
		$this->assertCount( 5, $form->getPreferenceSections() );
	}
}
