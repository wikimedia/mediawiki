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

namespace MediaWiki\JobQueue;

use ConfiguredReadOnlyMode;
use IBufferingStatsdDataFactory;
use JobQueueGroup;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\WikiMap\WikiMap;
use WANObjectCache;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * Class to construct JobQueueGroups
 *
 * @ingroup JobQueue
 * @since 1.37
 */
class JobQueueGroupFactory {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::JobClasses,
		MainConfigNames::JobTypeConf,
		MainConfigNames::JobTypesExcludedFromDefaultQueue,
		MainConfigNames::LocalDatabases,
	];

	/** @var JobQueueGroup[] */
	private $instances;

	/** @var ServiceOptions */
	private $options;

	/** @var ConfiguredReadOnlyMode */
	private $readOnlyMode;

	/** @var IBufferingStatsdDataFactory */
	private $statsdDataFactory;

	/** @var WANObjectCache */
	private $wanCache;

	/** @var GlobalIdGenerator */
	private $globalIdGenerator;

	/**
	 * @param ServiceOptions $options
	 * @param ConfiguredReadOnlyMode $readOnlyMode
	 * @param IBufferingStatsdDataFactory $statsdDataFactory
	 * @param WANObjectCache $wanCache
	 * @param GlobalIdGenerator $globalIdGenerator
	 */
	public function __construct(
		ServiceOptions $options,
		ConfiguredReadOnlyMode $readOnlyMode,
		IBufferingStatsdDataFactory $statsdDataFactory,
		WANObjectCache $wanCache,
		GlobalIdGenerator $globalIdGenerator
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->instances = [];
		$this->options = $options;
		$this->readOnlyMode = $readOnlyMode;
		$this->statsdDataFactory = $statsdDataFactory;
		$this->wanCache = $wanCache;
		$this->globalIdGenerator = $globalIdGenerator;
	}

	/**
	 * @since 1.37
	 *
	 * @param false|string $domain Wiki domain ID. False uses the current wiki domain ID
	 * @return JobQueueGroup
	 */
	public function makeJobQueueGroup( $domain = false ): JobQueueGroup {
		if ( $domain === false ) {
			$domain = WikiMap::getCurrentWikiDbDomain()->getId();
		}

		if ( !isset( $this->instances[$domain] ) ) {
			// Make sure jobs are not getting pushed to bogus wikis. This can confuse
			// the job runner system into spawning endless RPC requests that fail (T171371).
			$wikiId = WikiMap::getWikiIdFromDbDomain( $domain );
			if (
				!WikiMap::isCurrentWikiDbDomain( $domain ) &&
				!in_array( $wikiId, $this->options->get( MainConfigNames::LocalDatabases ) )
			) {
				$invalidDomain = true;
			} else {
				$invalidDomain = false;
			}

			$this->instances[$domain] = new JobQueueGroup(
				$domain,
				$this->readOnlyMode,
				$invalidDomain,
				$this->options->get( MainConfigNames::JobClasses ),
				$this->options->get( MainConfigNames::JobTypeConf ),
				$this->options->get( MainConfigNames::JobTypesExcludedFromDefaultQueue ),
				$this->statsdDataFactory,
				$this->wanCache,
				$this->globalIdGenerator
			);
		}

		return $this->instances[$domain];
	}
}
