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
 * @ingroup JobQueue
 *
 * @author Ostrzyciel
 */

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\RevertedTagUpdate;

/**
 * Job for deferring the execution of RevertedTagUpdate.
 *
 * @see \MediaWiki\Storage\RevertedTagUpdate
 * @since 1.36
 *
 * @ingroup JobQueue
 */
class RevertedTagUpdateJob extends Job implements GenericParameterJob {

	/**
	 * Returns a JobSpecification for this job.
	 *
	 * @param int $revertRevisionId
	 * @param EditResult $editResult
	 *
	 * @return JobSpecification
	 */
	public static function newSpec(
		int $revertRevisionId,
		EditResult $editResult
	) : JobSpecification {
		return new JobSpecification(
			'revertedTagUpdate',
			[
				'revertId' => $revertRevisionId,
				'editResult' => $editResult->jsonSerialize()
			]
		);
	}

	/**
	 * @param array $params
	 * @phan-param array{revertId:int,editResult:array} $params
	 */
	public function __construct( array $params ) {
		parent::__construct( 'revertedTagUpdate', $params );
	}

	/**
	 * Unpacks the job arguments and runs the update.
	 *
	 * @return bool
	 */
	public function run() {
		$services = MediaWikiServices::getInstance();
		$editResult = EditResult::newFromArray(
			$this->params['editResult']
		);

		$update = new RevertedTagUpdate(
			$services->getRevisionStore(),
			LoggerFactory::getInstance( 'RevertedTagUpdate' ),
			ChangeTags::getSoftwareTags(),
			$services->getDBLoadBalancer(),
			new ServiceOptions(
				RevertedTagUpdate::CONSTRUCTOR_OPTIONS,
				$services->getMainConfig()
			),
			$this->params['revertId'],
			$editResult
		);

		$update->doUpdate();
		return true;
	}
}
