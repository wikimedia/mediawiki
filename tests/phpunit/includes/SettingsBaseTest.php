<?php

/**
 * Tests for the SettingsBase class.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @since 1.20
 *
 * @ingroup Settings
 * @ingroup Test
 *
 * @group Settings
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SettingsBaseTest extends MediaWikiTestCase {

	public function testSingleton() {
		$this->assertInstanceOf( 'Settings', TestSettingsList::singleton() );
		$this->assertTrue( TestSettingsList::singleton() === TestSettingsList::singleton() );
		$this->assertFalse( TestSettingsList::singleton() === TestSettingsList::singleton( true ) );
		$this->assertTrue( TestSettingsList::singleton( true ) === TestSettingsList::singleton() );
	}

	public function testGet() {
		foreach ( TestSettingsList::getTestSetSettings() as $settingName => $settingValue ) {
			$this->assertEquals( $settingValue, TestSettingsList::get( $settingName ) );
		}
	}

	public function testGetSetting() {
		$settings = TestSettingsList::singleton();

		foreach ( TestSettingsList::getTestSetSettings() as $settingName => $settingValue ) {
			$this->assertEquals( $settingValue, $settings->getSetting( $settingName ) );
		}
	}

	public function testHas() {
		foreach ( array_keys( TestSettingsList::getTestSetSettings() ) as $settingName ) {
			$this->assertTrue( TestSettingsList::has( $settingName ) );
		}

		$this->assertFalse( TestSettingsList::has( 'I dont think therefore I dont exist' ) );
	}

	public function testHasSetting() {
		$settings = TestSettingsList::singleton();

		foreach ( array_keys( TestSettingsList::getTestSetSettings() ) as $settingName ) {
			$this->assertTrue( $settings->hasSetting( $settingName ) );
		}

		$this->assertFalse( $settings->hasSetting( 'I dont think therefore I dont exist' ) );
	}

	public function testSet() {
		foreach ( TestSettingsList::getTestDefaults() as $settingName => $settingValue ) {
			TestSettingsList::set( $settingName, $settingValue );
			$this->assertEquals( $settingValue, TestSettingsList::get( $settingName ) );
		}

		foreach ( TestSettingsList::getTestSetSettings() as $settingName => $settingValue ) {
			TestSettingsList::set( $settingName, $settingValue );
			$this->assertEquals( $settingValue, TestSettingsList::get( $settingName ) );
		}
	}

	public function testSetSetting() {
		$settings = TestSettingsList::singleton();

		foreach ( TestSettingsList::getTestDefaults() as $settingName => $settingValue ) {
			$settings->setSetting( $settingName, $settingValue );
			$this->assertEquals( $settingValue, TestSettingsList::get( $settingName ) );
		}

		foreach ( TestSettingsList::getTestSetSettings() as $settingName => $settingValue ) {
			$settings->setSetting( $settingName, $settingValue );
			$this->assertEquals( $settingValue, TestSettingsList::get( $settingName ) );
		}
	}

}

class TestSettingsList extends SettingsBase {

	public static function getTestDefaults() {
		return array(
			'awesome' => null,
			'answer' => 0,
			'amount' => 9001,
			'foo' => 'bar',
		);
	}

	public static function getTestSetSettings() {
		return array(
			'awesome' => true,
			'answer' => 42,
			'amount' => 9001,
		);
	}

	public function getDefaultSettings() {
		return static::getTestDefaults();
	}

	public function getSetSettings() {
		return static::getTestSetSettings();
	}

}
