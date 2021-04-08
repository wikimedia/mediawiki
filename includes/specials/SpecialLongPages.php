<?php
/**
 * Implements Special:Longpages
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
 * @ingroup SpecialPage
 */

use MediaWiki\Cache\LinkBatchFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @ingroup SpecialPage
 */
class SpecialLongPages extends SpecialShortPages {

	/**
	 * @param NamespaceInfo $namespaceInfo
	 * @param ILoadBalancer $loadBalancer
	 * @param LinkBatchFactory $linkBatchFactory
	 */
	public function __construct(
		NamespaceInfo $namespaceInfo,
		ILoadBalancer $loadBalancer,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct(
			$namespaceInfo,
			$loadBalancer,
			$linkBatchFactory
		);
		$this->mName = 'Longpages';
	}

	protected function sortDescending() {
		return true;
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
