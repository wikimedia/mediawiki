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
 * @file
 */

namespace MediaWiki\Storage;

use Wikimedia\Rdbms\ILBFactory;
use WANObjectCache;
use Psr\Log\LoggerInterface;

class NameTableStoreFactory {
	private static $info;
	private $stores = [];

	/** @var ILBFactory */
	private $lbFactory;

	/** @var WANObjectCache */
	private $cache;

	/** @var LoggerInterface */
	private $logger;

	private static function getTableInfo() {
		if ( self::$info ) {
			return self::$info;
		}
		self::$info = [
			'change_tag_def' => [
				'idField' => 'ctd_id',
				'nameField' => 'ctd_name',
				'normalizationCallback' => null,
				'insertCallback' => function ( $insertFields ) {
					$insertFields['ctd_user_defined'] = 0;
					$insertFields['ctd_count'] = 0;
					return $insertFields;
				}
			],

			'content_models' => [
				'idField' => 'model_id',
				'nameField' => 'model_name',
				/**
				 * No strtolower normalization is added to the service as there are examples of
				 * extensions that do not stick to this assumption.
				 * - extensions/examples/DataPages define( 'CONTENT_MODEL_XML_DATA','XML_DATA' );
				 * - extensions/Scribunto define( 'CONTENT_MODEL_SCRIBUNTO', 'Scribunto' );
				 */
			],

			'slot_roles' => [
				'idField' => 'role_id',
				'nameField' => 'role_name',
				'normalizationCallback' => 'strtolower',
			],
		];
		return self::$info;
	}

	public function __construct(
		ILBFactory $lbFactory,
		WANObjectCache $cache,
		LoggerInterface $logger
	) {
		$this->lbFactory = $lbFactory;
		$this->cache = $cache;
		$this->logger = $logger;
	}

	/**
	 * Get a NameTableStore for a specific table
	 *
	 * @param string $tableName The table name
	 * @param string|false $wiki The target wiki ID, or false for the current wiki
	 * @return NameTableStore
	 */
	public function get( $tableName, $wiki = false ) : NameTableStore {
		$infos = self::getTableInfo();
		if ( !isset( $infos[$tableName] ) ) {
			throw new \InvalidArgumentException( "Invalid table name \$tableName" );
		}
		if ( $wiki === $this->lbFactory->getLocalDomainID() ) {
			$wiki = false;
		}

		if ( isset( $this->stores[$tableName][$wiki] ) ) {
			return $this->stores[$tableName][$wiki];
		}

		$info = $infos[$tableName];
		$store = new NameTableStore(
			$this->lbFactory->getMainLB( $wiki ),
			$this->cache,
			$this->logger,
			$tableName,
			$info['idField'],
			$info['nameField'],
			$info['normalizationCallback'] ?? null,
			$wiki,
			$info['insertCallback'] ?? null
		);
		$this->stores[$tableName][$wiki] = $store;
		return $store;
	}

	/**
	 * Get a NameTableStore for the change_tag_def table
	 *
	 * @param string|bool $wiki
	 * @return NameTableStore
	 */
	public function getChangeTagDef( $wiki = false ) : NameTableStore {
		return $this->get( 'change_tag_def', $wiki );
	}

	/**
	 * Get a NameTableStore for the content_models table
	 *
	 * @param string|bool $wiki
	 * @return NameTableStore
	 */
	public function getContentModels( $wiki = false ) : NameTableStore {
		return $this->get( 'content_models', $wiki );
	}

	/**
	 * Get a NameTableStore for the slot_roles table
	 *
	 * @param string|bool $wiki
	 * @return NameTableStore
	 */
	public function getSlotRoles( $wiki = false ) : NameTableStore {
		return $this->get( 'slot_roles', $wiki );
	}
}
