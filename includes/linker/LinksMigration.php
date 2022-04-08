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
				'config' => 'TemplateLinksSchemaMigrationStage',
				'page_id' => 'tl_from',
				'ns' => 'tl_namespace',
				'title' => 'tl_title',
				'target_id' => 'tl_target_id',
			],
		];

	/**
	 * @param Config $config
	 * @param LinkTargetLookup $linktargetLookup
	 */
	public function __construct( Config $config, LinkTargetLookup $linktargetLookup ) {
		$this->config = $config;
		$this->linkTargetLookup = $linktargetLookup;
	}

	public function getLinksConditions( string $table, LinkTarget $linkTarget ): array {
		if ( !isset( self::$mapping[$table] ) ) {
			throw new InvalidArgumentException(
				"LinksMigration doesn't support the $table table yet"
			);
		}
		if ( $this->config->get( self::$mapping[$table]['config'] ) & SCHEMA_COMPAT_READ_NEW ) {
			$targetId = $this->linkTargetLookup->getLinkTargetId( $linkTarget );
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
}
