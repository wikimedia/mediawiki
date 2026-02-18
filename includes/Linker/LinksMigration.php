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
			'page_id' => 'tl_from',
			// Used by the updater only
			'ns' => 'tl_namespace',
			// Used by the updater only
			'title' => 'tl_title',
			'target_id' => 'tl_target_id',
		],
		'pagelinks' => [
			'page_id' => 'pl_from',
			// Used by the updater only
			'ns' => 'pl_namespace',
			// Used by the updater only
			'title' => 'pl_title',
			'target_id' => 'pl_target_id',
		],
		'categorylinks' => [
			'page_id' => 'cl_from',
			// Used by the updater only
			'ns' => NS_CATEGORY,
			// Used by the updater only
			'title' => 'cl_to',
			'target_id' => 'cl_target_id',
		],
		'existencelinks' => [
			'page_id' => 'exl_from',
			'target_id' => 'exl_target_id',
		],
		'imagelinks' => [
			'page_id' => 'il_from',
			// Used by the updater only
			'ns' => NS_FILE,
			// Used by the updater only
			'title' => 'il_to',
			'target_id' => 'il_target_id',
		],
	];

	/** @var string[] */
	public static $prefixToTableMapping = [
		'tl' => 'templatelinks',
		'pl' => 'pagelinks',
		'cl' => 'categorylinks',
		'exl' => 'existencelinks',
		'il' => 'imagelinks',
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
		$targetId = $this->linkTargetLookup->getLinkTargetId( $linkTarget );
		// Not found, it shouldn't pick anything
		if ( !$targetId ) {
			return [ '1=0' ];
		}
		return [ self::$mapping[$table]['target_id'] => $targetId ];
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
		$targetId = self::$mapping[$table]['target_id'];
		if ( $joinTable === 'linktarget' ) {
			$tables = [ $table, 'linktarget' ];
		} else {
			$tables = [ 'linktarget', $table ];
		}
		return [
			'tables' => $tables,
			'fields' => [ $targetId, 'lt_namespace', 'lt_title' ],
			'joins' => [ $joinTable => [ $joinType, [ "$targetId = lt_id" ] ] ],
		];
	}

	public function getTitleFields( string $table ): array {
		$this->assertMapping( $table );

		return [ 'lt_namespace', 'lt_title' ];
	}

	private function assertMapping( string $table ) {
		if ( !isset( self::$mapping[$table] ) ) {
			throw new InvalidArgumentException( "LinksMigration doesn't support the $table table yet" );
		}
	}
}
