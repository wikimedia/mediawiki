<?php
/**
 * Job queue task description base code.
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
 * @ingroup JobQueue
 */

/**
 * Job queue task description interface
 *
 * @ingroup JobQueue
 * @since 1.23
 */
interface IJobSpecification {
	/**
	 * @return string Job type
	 */
	public function getType();

	/**
	 * @return array
	 */
	public function getParams();

	/**
	 * @return int|null UNIX timestamp to delay running this job until, otherwise null
	 */
	public function getReleaseTimestamp();

	/**
	 * @return bool Whether only one of each identical set of jobs should be run
	 */
	public function ignoreDuplicates();

	/**
	 * Subclasses may need to override this to make duplication detection work.
	 * The resulting map conveys everything that makes the job unique. This is
	 * only checked if ignoreDuplicates() returns true, meaning that duplicate
	 * jobs are supposed to be ignored.
	 *
	 * @return array Map of key/values
	 */
	public function getDeduplicationInfo();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return array
	 * @since 1.26
	 */
	public function getRootJobParams();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool
	 * @since 1.22
	 */
	public function hasRootJobParams();

	/**
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool Whether this is job is a root job
	 */
	public function isRootJob();

	/**
	 * @return Title Descriptive title (this can simply be informative)
	 */
	public function getTitle();
}

/**
 * Job queue task description base code
 *
 * Example usage:
 * @code
 * $job = new JobSpecification(
 *		'null',
 *		array( 'lives' => 1, 'usleep' => 100, 'pi' => 3.141569 ),
 *		array( 'removeDuplicates' => 1 ),
 *		Title::makeTitle( NS_SPECIAL, 'nullity' )
 * );
 * JobQueueGroup::singleton()->push( $job )
 * @endcode
 *
 * @ingroup JobQueue
 * @since 1.23
 */
class JobSpecification implements IJobSpecification {
	/** @var string */
	protected $type;

	/** @var array Array of job parameters or false if none */
	protected $params;

	/** @var Title */
	protected $title;

	/** @var array */
	protected $opts;

	/**
	 * @param string $type
	 * @param array $params Map of key/values
	 * @param array $opts Map of key/values; includes 'removeDuplicates'
	 * @param Title $title Optional descriptive title
	 */
	public function __construct(
		$type, array $params, array $opts = [], Title $title = null
	) {
		$this->validateParams( $params );
		$this->validateParams( $opts );

		$this->type = $type;
		$this->params = $params;
		$this->title = $title ?: Title::makeTitle( NS_SPECIAL, 'Badtitle/' . get_class( $this ) );
		$this->opts = $opts;
	}

	/**
	 * @param array $params
	 */
	protected function validateParams( array $params ) {
		foreach ( $params as $p => $v ) {
			if ( is_array( $v ) ) {
				$this->validateParams( $v );
			} elseif ( !is_scalar( $v ) && $v !== null ) {
				throw new UnexpectedValueException( "Job parameter $p is not JSON serializable." );
			}
		}
	}

	public function getType() {
		return $this->type;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getParams() {
		return $this->params;
	}

	public function getReleaseTimestamp() {
		return isset( $this->params['jobReleaseTimestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->params['jobReleaseTimestamp'] )
			: null;
	}

	public function ignoreDuplicates() {
		return !empty( $this->opts['removeDuplicates'] );
	}

	public function getDeduplicationInfo() {
		$info = [
			'type' => $this->getType(),
			'namespace' => $this->getTitle()->getNamespace(),
			'title' => $this->getTitle()->getDBkey(),
			'params' => $this->getParams()
		];
		if ( is_array( $info['params'] ) ) {
			// Identical jobs with different "root" jobs should count as duplicates
			unset( $info['params']['rootJobSignature'] );
			unset( $info['params']['rootJobTimestamp'] );
			// Likewise for jobs with different delay times
			unset( $info['params']['jobReleaseTimestamp'] );
		}

		return $info;
	}

	public function getRootJobParams() {
		return [
			'rootJobSignature' => isset( $this->params['rootJobSignature'] )
				? $this->params['rootJobSignature']
				: null,
			'rootJobTimestamp' => isset( $this->params['rootJobTimestamp'] )
				? $this->params['rootJobTimestamp']
				: null
		];
	}

	public function hasRootJobParams() {
		return isset( $this->params['rootJobSignature'] )
			&& isset( $this->params['rootJobTimestamp'] );
	}

	public function isRootJob() {
		return $this->hasRootJobParams() && !empty( $this->params['rootJobIsSelf'] );
	}

	/**
	 * @return array Field/value map that can immediately be serialized
	 * @since 1.25
	 */
	public function toSerializableArray() {
		return [
			'type'   => $this->type,
			'params' => $this->params,
			'opts'   => $this->opts,
			'title'  => [
				'ns'  => $this->title->getNamespace(),
				'key' => $this->title->getDBkey()
			]
		];
	}

	/**
	 * @param array $map Field/value map
	 * @return JobSpecification
	 * @since 1.25
	 */
	public static function newFromArray( array $map ) {
		$title = Title::makeTitle( $map['title']['ns'], $map['title']['key'] );

		return new self( $map['type'], $map['params'], $map['opts'], $title );
	}
}
