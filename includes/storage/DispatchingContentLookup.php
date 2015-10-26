<?php

namespace MediaWiki\Storage;

use LazyRegistry;
use OutOfBoundsException;
use Title;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\ParameterAssertionException;

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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * RevisionContentLookup that dispatches based on slot name and namespace.
 */
class DispatchingContentLookup implements RevisionContentLookup {

	/**
	 * @var LazyRegistry
	 */
	private $lookupRegistry;

	/**
	 * @var string[]
	 */
	private $lookupsBySlot;

	/**
	 * @var string[]
	 */
	private $mainSlotLookupsByModel;

	/**
	 * @param callable[] $lookupConstructors Constructor callbacks for creating
	 *        RevisionContentLookups, with arbitrary names as keys.
	 * @param string[] $lookupsBySlot Mapping of slot names to lookup names.
	 * @param string[] $mainSlotLookupsByModel Mapping of content model to lookup names
	 */
	public function __construct(
		array $lookupConstructors,
		array $lookupsBySlot,
		array $mainSlotLookupsByModel
	) {
		self::checkLookupNames( $lookupConstructors, $lookupsBySlot, '$lookupsBySlot' );
		self::checkLookupNames( $lookupConstructors, $mainSlotLookupsByModel, '$mainSlotLookupsByModel' );

		$this->lookupRegistry = new LazyRegistry(
			'MediaWiki\Storage\RevisionContentLookup',
			$lookupConstructors
		);

		$this->lookupsBySlot = $lookupsBySlot;
		$this->mainSlotLookupsByModel = $mainSlotLookupsByModel;
	}

	/**
	 * Assert that all values in $lookupSlots are keys in $lookupConstructors.
	 *
	 * @param callable[] $lookupConstructors
	 * @param string[] $lookupSlots
	 * @param string $name The name of the parameter.
	 *
	 * @throws ParameterAssertionException if $condition is not true.
	 */
	private static function checkLookupNames( array $lookupConstructors, array $lookupSlots, $name ) {
		$unknown = array_diff( array_values( $lookupSlots ), array_keys( $lookupConstructors ) );
		Assert::parameter( empty( $unknown ), $name, 'Unknown lookup name(s): ' . implode( ',', $unknown ) );
	}

	/**
	 * @param Title $title
	 * @param int $revisionId The revision ID (not 0)
	 * @param string $slot The slot name.
	 *
	 * @throws RevisionContentException
	 * @return RevisionSlot
	 */
	function getRevisionSlot( Title $title, $revisionId, $slot = 'main' ) {
		$lookupName = null;

		if ( $slot === 'main' ) {
			$model = $title->getContentModel();
			if ( isset( $this->mainSlotLookupsByModel[$model] ) ) {
				$lookupName = $this->mainSlotLookupsByModel[$model];
			}
		}

		if ( $lookupName === null ) {
			$major = $this->stripNameSuffix( $slot );
			if ( isset( $this->lookupsBySlot[$major] ) ) {
				$lookupName = $this->lookupsBySlot[$major];
			}
		}

		if ( $lookupName === null ) {
			throw new NoSuchSlotException( $title, $revisionId, $slot );
		}

		try {
			$lookup = $this->getLookup( $lookupName );
			return $lookup->getRevisionSlot( $title, $revisionId, $slot );
		} catch ( OutOfBoundsException $ex ) {
			// This can only happen if  $this->mainSlotLookupsByModel or $this->lookupsBySlot
			// refer to an unknown lookup name. The constructor should prevent that.
			throw new RevisionContentException( $ex->getMessage(), $title, $revisionId, $slot );
		}
	}

	/**
	 * Strip any suffix from the slot name. Only the "major" part of the slot name should be
	 * used to find a lookup. This allows suffixes to be used in slot names to implement
	 * arbitrary "sub-slots" all using the same lookup (resp. storage) mechanism.
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	private function stripNameSuffix( $name ) {
		return preg_replace( '!^(.*?)[.:/#].*$!', '$1', $name );
	}

	/**
	 * @param string $name
	 *
	 * @return RevisionContentLookup
	 * @throws RevisionContentException
	 */
	private function getLookup( $name ) {
		return $this->lookupRegistry->getInstance( $name );
	}

	/**
	 * @return string[]
	 */
	private function getLookupNames() {
		return array_unique( array_merge(
			array_values( $this->lookupsBySlot ),
			array_values( $this->mainSlotLookupsByModel )
		) );
	}

}
