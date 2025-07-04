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

use MediaWiki\Http\Telemetry;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use UnexpectedValueException;

/**
 * Job queue task description base code.
 *
 * Example usage:
 * @code
 * $job = new JobSpecification(
 *		'null',
 *		[ 'lives' => 1, 'usleep' => 100, 'pi' => 3.141569 ],
 *		[ 'removeDuplicates' => 1 ]
 * );
 * MediaWikiServices::getInstance()->getJobQueueGroup()->push( $job )
 * @endcode
 *
 * @newable
 * @since 1.23
 * @ingroup JobQueue
 */
class JobSpecification implements IJobSpecification {
	/** @var string */
	protected $type;

	/** @var array Array of job parameters or false if none */
	protected $params;

	/** @var PageReference */
	protected $page;

	/** @var array */
	protected $opts;

	/**
	 * @param string $type
	 * @param array $params Map of key/values
	 *   'requestId' - The request ID, as obtained from {@link Telemetry::getRequestId}. If not set,
	 *   the value will be populated from the current instance of {@link Telemetry}.
	 * @param array $opts Map of key/values
	 *   'removeDuplicates' key - whether to remove duplicate jobs
	 *   'removeDuplicatesIgnoreParams' key - array with parameters to ignore for deduplication
	 * @param PageReference|null $page
	 */
	public function __construct(
		$type, array $params, array $opts = [], ?PageReference $page = null
	) {
		$params += [
			'requestId' => Telemetry::getInstance()->getRequestId(),
		];
		$this->validateParams( $params );
		$this->validateParams( $opts );

		$this->type = $type;
		if ( $page ) {
			// Make sure JobQueue classes can pull the title from parameters alone
			if ( $page->getDBkey() !== '' ) {
				$params += [
					'namespace' => $page->getNamespace(),
					'title' => $page->getDBkey()
				];
			}
		} else {
			// We aim to remove the page from job specification and all we need
			// is namespace/dbkey, so use LOCAL no matter what.
			$page = PageReferenceValue::localReference( NS_SPECIAL, 'Badtitle/' . __CLASS__ );
		}
		$this->params = $params;
		$this->page = $page;
		$this->opts = $opts;
	}

	protected function validateParams( array $params ) {
		foreach ( $params as $p => $v ) {
			if ( is_array( $v ) ) {
				$this->validateParams( $v );
			} elseif ( !is_scalar( $v ) && $v !== null ) {
				throw new UnexpectedValueException( "Job parameter $p is not JSON serializable." );
			}
		}
	}

	/** @inheritDoc */
	public function getType() {
		return $this->type;
	}

	/** @inheritDoc */
	public function getParams() {
		return $this->params;
	}

	/** @inheritDoc */
	public function getReleaseTimestamp() {
		return isset( $this->params['jobReleaseTimestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->params['jobReleaseTimestamp'] )
			: null;
	}

	/** @inheritDoc */
	public function ignoreDuplicates() {
		return !empty( $this->opts['removeDuplicates'] );
	}

	/** @inheritDoc */
	public function getDeduplicationInfo() {
		$info = [
			'type' => $this->getType(),
			'params' => $this->getParams()
		];
		if ( is_array( $info['params'] ) ) {
			// Identical jobs with different "root" jobs should count as duplicates
			unset( $info['params']['rootJobSignature'] );
			unset( $info['params']['rootJobTimestamp'] );
			// Likewise for jobs with different delay times
			unset( $info['params']['jobReleaseTimestamp'] );
			// Identical jobs from different requests should count as duplicates
			unset( $info['params']['requestId'] );
			if ( isset( $this->opts['removeDuplicatesIgnoreParams'] ) ) {
				foreach ( $this->opts['removeDuplicatesIgnoreParams'] as $field ) {
					unset( $info['params'][$field] );
				}
			}
		}

		return $info;
	}

	/** @inheritDoc */
	public function getRootJobParams() {
		return [
			'rootJobSignature' => $this->params['rootJobSignature'] ?? null,
			'rootJobTimestamp' => $this->params['rootJobTimestamp'] ?? null
		];
	}

	/** @inheritDoc */
	public function hasRootJobParams() {
		return isset( $this->params['rootJobSignature'] )
			&& isset( $this->params['rootJobTimestamp'] );
	}

	/** @inheritDoc */
	public function isRootJob() {
		return $this->hasRootJobParams() && !empty( $this->params['rootJobIsSelf'] );
	}

	/**
	 * @deprecated since 1.41
	 * @return array Field/value map that can immediately be serialized
	 * @since 1.25
	 */
	public function toSerializableArray() {
		wfDeprecated( __METHOD__, '1.41' );
		return [
			'type'   => $this->type,
			'params' => $this->params,
			'opts'   => $this->opts,
			'title'  => [
				'ns'  => $this->page->getNamespace(),
				'key' => $this->page->getDBkey()
			]
		];
	}

	/**
	 * @param array $map Field/value map
	 * @return JobSpecification
	 * @since 1.25
	 */
	public static function newFromArray( array $map ) {
		return new self(
			$map['type'],
			$map['params'],
			$map['opts'],
			PageReferenceValue::localReference( $map['title']['ns'], $map['title']['key'] )
		);
	}
}

/** @deprecated class alias since 1.44 */
class_alias( JobSpecification::class, 'JobSpecification' );
