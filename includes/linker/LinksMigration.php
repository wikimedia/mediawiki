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

namespace MediaWiki\Linker;

use Config;
use InvalidArgumentException;
use MediaWiki\MainConfigNames;

/**
 * Service for compat reading of links tables
 *
 * @since 1.39
 */
class LinksMigration {

	/** @var Config */
	private $config;

	/** @var LinkTargetLookup */
	private $linkTargetLookup;

	public static $mapping = [
		'templatelinks' => [
			'config' => MainConfigNames::TemplateLinksSchemaMigrationStage,
			'page_id' => 'tl_from',
			'ns' => 'tl_namespace',
			'title' => 'tl_title',
			'target_id' => 'tl_target_id',
			'deprecated_configs' => [ SCHEMA_COMPAT_OLD ],
		],
	];

	public static $prefixToTableMapping = [
		'tl' => 'templatelinks'
	];

	/**
	 * @param Config $config
	 * @param LinkTargetLookup $linktargetLookup
	 */
	public function __construct( Config $config, LinkTargetLookup $linktargetLookup ) {
		$this->config = $config;
		$this->linkTargetLookup = $linktargetLookup;
	}

	/**
	 * Return the conditions to be used in querying backlinks to a page
	 *
	 * @param string $table
	 * @param LinkTarget $linkTarget
	 * @return array
	 */
	public function getLinksConditions( string $table, LinkTarget $linkTarget ): array {
		$this->assertMapping( $table );
		if ( $this->config->get( self::$mapping[$table]['config'] ) & SCHEMA_COMPAT_READ_NEW ) {
			$targetId = $this->linkTargetLookup->getLinkTargetId( $linkTarget );
			// Not found, it shouldn't pick anything
			if ( !$targetId ) {
				return [ '1=0' ];
			}
			return [
				self::$mapping[$table]['target_id'] => $targetId,
			];
		} else {
			return [
				self::$mapping[$table]['ns'] => $linkTarget->getNamespace(),
				self::$mapping[$table]['title'] => $linkTarget->getDBkey(),
			];
		}
	}

	/**
	 * Return the query to be used when you want to or from a group of pages
	 *
	 * @param string $table
	 * @param string $joinTable table to end the join chain. Most of the time it's linktarget
	 * @param string $joinType
	 * @return array
	 */
	public function getQueryInfo( string $table, string $joinTable = 'linktarget', string $joinType = 'JOIN' ) {
		$this->assertMapping( $table );
		if ( $this->config->get( self::$mapping[$table]['config'] ) & SCHEMA_COMPAT_READ_NEW ) {
			$targetId = self::$mapping[$table]['target_id'];
			if ( $joinTable === 'linktarget' ) {
				$tables = [ $table, 'linktarget' ];
			} else {
				$tables = [ 'linktarget', $table ];
			}
			return [
				'tables' => $tables,
				'fields' => [
					$targetId,
					'lt_namespace',
					'lt_title'
				],
				'joins' => [ $joinTable => [
					$joinType,
					[ "$targetId=lt_id" ]
				] ],
			];
		} else {
			return [
				'fields' => [
					self::$mapping[$table]['ns'],
					self::$mapping[$table]['title']
				],
				'tables' => [ $table ],
				'joins' => [],
			];
		}
	}

	public function getTitleFields( $table ) {
		$this->assertMapping( $table );

		if ( $this->config->get( self::$mapping[$table]['config'] ) & SCHEMA_COMPAT_READ_NEW ) {
			return [ 'lt_namespace', 'lt_title' ];
		} else {
			return [ self::$mapping[$table]['ns'], self::$mapping[$table]['title'] ];
		}
	}

	private function assertMapping( string $table ) {
		if ( !isset( self::$mapping[$table] ) ) {
			throw new InvalidArgumentException(
				"LinksMigration doesn't support the $table table yet"
			);
		}

		$config = $this->config->get( self::$mapping[$table]['config'] );
		if ( in_array( $config, self::$mapping[$table]['deprecated_configs'] ) ) {
			throw new InvalidArgumentException(
				"LinksMigration config $config on $table table is not supported anymore"
			);
		}
	}
}
