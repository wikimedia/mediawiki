<?php
/**
 * Factory to create LinkBatch objects for querying page existence.
 *
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
 * @ingroup Cache
 */

namespace MediaWiki\Cache;

use GenderCache;
use Language;
use LinkBatch;
use LinkCache;
use MediaWiki\Linker\LinkTarget;
use TitleFormatter;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup Cache
 * @since 1.35
 */
class LinkBatchFactory {

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * @var GenderCache
	 */
	private $genderCache;

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	public function __construct(
		LinkCache $linkCache,
		TitleFormatter $titleFormatter,
		Language $contentLanguage,
		GenderCache $genderCache,
		ILoadBalancer $loadBalancer
	) {
		$this->linkCache = $linkCache;
		$this->titleFormatter = $titleFormatter;
		$this->contentLanguage = $contentLanguage;
		$this->genderCache = $genderCache;
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @param iterable|LinkTarget[] $initialItems Initial items to be added to the batch
	 * @return LinkBatch
	 */
	public function newLinkBatch( iterable $initialItems = [] ) : LinkBatch {
		$batch = new LinkBatch(
			[],
			$this->linkCache,
			$this->titleFormatter,
			$this->contentLanguage,
			$this->genderCache,
			$this->loadBalancer
		);

		foreach ( $initialItems as $item ) {
			$batch->addObj( $item );
		}

		return $batch;
	}
}
