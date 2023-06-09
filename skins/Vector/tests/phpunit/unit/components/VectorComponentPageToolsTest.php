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
 * @since 1.35
 */

namespace MediaWiki\Skins\Vector\Tests\Unit\Components;

use MediaWiki\Skins\Vector\Components\VectorComponentPageTools;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use Message;
use MessageLocalizer;
use User;

/**
 * @group Vector
 * @group Components
 * @coversDefaultClass \MediaWiki\Skins\Vector\Components\VectorComponentPageTools
 */
class VectorComponentPageToolsTest extends \MediaWikiUnitTestCase {
	public static function getPinnableHeaderData( $data = [] ) {
		return array_merge( [
			'is-pinned' => false,
			'label' => 'vector-page-tools-label',
			'label-tag-name' => 'div',
			'pin-label' => 'vector-pin-element-label',
			'unpin-label' => 'vector-unpin-element-label',
			'data-feature-name' => 'page-tools-pinned',
			'data-pinnable-element-id' => 'vector-page-tools',
			'data-unpinned-container-id' => 'vector-page-tools-unpinned-container',
			'data-pinned-container-id' => 'vector-page-tools-pinned-container',
		], $data );
	}

	public static function provideConstructorData() {
		$menus = [ [
			'id' => 'p-cactions',
			'array-items' => [ [
				'id' => 'ca-delete',
				'html-item' => "<li><a><span>Delete</span></a></li>"
			] ]
		], [
			'id' => 'p-tb',
			'array-items' => [ [
				'id' => 't-whatlinkshere',
				'html-item' => "<li><a><span>What links here</span></a></li>"
			] ]
		] ];

		$expectedMenus = $menus;
		$expectedMenus[ 0 ][ 'label' ] = 'vector-page-tools-actions-label';
		$expectedMenus[ 1 ][ 'label' ] = 'vector-page-tools-general-label';

		return [
			[
				$menus,
				false,
				false,
				[
					'id' => 'vector-page-tools',
					'is-pinned' => false,
					'data-pinnable-header' => self::getPinnableHeaderData(),
					'data-menus' => $expectedMenus
				]
			],
			[
				$menus,
				false,
				true,
				[
					'id' => 'vector-page-tools',
					'is-pinned' => true,
					'data-pinnable-header' => self::getPinnableHeaderData( [
						'is-pinned' => true,
					] ),
					'data-menus' => $expectedMenus
				]
			],
			[
				$menus,
				true,
				false,
				[
					'id' => 'vector-page-tools',
					'is-pinned' => false,
					'data-pinnable-header' => self::getPinnableHeaderData(),
					'data-menus' => $expectedMenus
				]
			]
		];
	}

	/**
	 * @covers ::getTemplateData
	 * @dataProvider provideConstructorData
	 */
	public function testGetTemplateData(
		array $menus,
		bool $isRegistered,
		bool $isPinned,
		array $expected
	) {
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )->willReturnCallback( function ( $key, ...$params ) {
			$msg = $this->createMock( Message::class );
			$msg->method( '__toString' )->willReturn( $key );
			$msg->method( 'text' )->willReturn( $key );
			return $msg;
		} );
		$user = $this->createMock( User::class );
		$user->method( 'isRegistered' )->willReturn( $isRegistered );
		$featureManager = $this->createMock( FeatureManager::class );
		$featureManager->method( 'isFeatureEnabled' )->willReturn( $isPinned );

		$pageTools = new VectorComponentPageTools(
			$menus,
			$localizer,
			$user,
			$featureManager
		);
		$this->assertEquals( $expected, $pageTools->getTemplateData() );
	}
}
