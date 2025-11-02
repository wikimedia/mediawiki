<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Linker;

use InvalidArgumentException;
use MediaWiki\Config\Config;

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

	/** @var array[] */
	public static $mapping = [
		'templatelinks' => [
			'config' => -1,
			'page_id' => 'tl_from',
			// Used by the updater only
			'ns' => 'tl_namespace',
			// Used by the updater only
			'title' => 'tl_title',
			'target_id' => 'tl_target_id',
			'deprecated_configs' => [],
		],
		'pagelinks' => [
			'config' => -1,
			'page_id' => 'pl_from',
			'ns' => 'pl_namespace',
			'title' => 'pl_title',
			'target_id' => 'pl_target_id',
			'deprecated_configs' => [],
		],
		'categorylinks' => [
			'config' => -1,
			'page_id' => 'cl_from',
			'ns' => 14,
			'title' => 'cl_to',
			'target_id' => 'cl_target_id',
			'deprecated_configs' => [],
		],
		'existencelinks' => [
			'config' => -1,
			'page_id' => 'exl_from',
			// Fake field just for phan
			'ns' => 'exl_namespace',
			// Fake field just for phan
			'title' => 'exl_title',
			'target_id' => 'exl_target_id',
			'deprecated_configs' => [],
		],
	];

	/** @var string[] */
	public static $prefixToTableMapping = [
		'tl' => 'templatelinks',
		'pl' => 'pagelinks',
		'cl' => 'categorylinks',
		'exl' => 'existencelinks',
	];

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
		if ( $this->isMigrationReadNew( $table ) ) {
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
		if ( $this->isMigrationReadNew( $table ) ) {
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

	public function getTitleFields( string $table ): array {
		$this->assertMapping( $table );

		if ( $this->isMigrationReadNew( $table ) ) {
			return [ 'lt_namespace', 'lt_title' ];
		} else {
			return [ self::$mapping[$table]['ns'], self::$mapping[$table]['title'] ];
		}
	}

	private function isMigrationReadNew( string $table ): bool {
		return self::$mapping[$table]['config'] === -1 ||
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$this->config->get( self::$mapping[$table]['config'] ) & SCHEMA_COMPAT_READ_NEW;
	}

	private function assertMapping( string $table ) {
		if ( !isset( self::$mapping[$table] ) ) {
			throw new InvalidArgumentException(
				"LinksMigration doesn't support the $table table yet"
			);
		}

		if ( self::$mapping[$table]['config'] !== -1 && self::$mapping[$table]['deprecated_configs'] ) {
			// @phan-suppress-next-line PhanTypeMismatchArgument
			$config = $this->config->get( self::$mapping[$table]['config'] );
			foreach ( self::$mapping[$table]['deprecated_configs'] as $deprecatedConfig ) {
				if ( $config & $deprecatedConfig ) {
					throw new InvalidArgumentException(
						"LinksMigration config $config on $table table is not supported anymore"
					);
				}
			}

		}
	}
}
