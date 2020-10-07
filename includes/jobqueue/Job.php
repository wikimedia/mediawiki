<?php
/**
 * Job queue task base code.
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
 * @defgroup JobQueue JobQueue
 */

/**
 * Class to both describe a background job and handle jobs.
 * To push jobs onto queues, use JobQueueGroup::singleton()->push();
 *
 * @stable to extend
 *
 * @ingroup JobQueue
 */
abstract class Job implements RunnableJob {
	/** @var string */
	public $command;

	/** @var array Array of job parameters */
	public $params;

	/** @var array Additional queue metadata */
	public $metadata = [];

	/** @var Title */
	protected $title;

	/** @var bool Expensive jobs may set this to true */
	protected $removeDuplicates = false;

	/** @var string Text for error that occurred last */
	protected $error;

	/** @var callable[] */
	protected $teardownCallbacks = [];

	/** @var int Bitfield of JOB_* class constants */
	protected $executionFlags = 0;

	/**
	 * Create the appropriate object to handle a specific job
	 *
	 * @param string $command Job command
	 * @param array|Title $params Job parameters
	 * @throws InvalidArgumentException
	 * @return Job
	 */
	public static function factory( $command, $params = [] ) {
		global $wgJobClasses;

		if ( $params instanceof Title ) {
			// Backwards compatibility for old signature ($command, $title, $params)
			$title = $params;
			$params = func_num_args() >= 3 ? func_get_arg( 2 ) : [];
		} elseif ( isset( $params['namespace'] ) && isset( $params['title'] ) ) {
			// Handle job classes that take title as constructor parameter.
			// If a newer classes like GenericParameterJob uses these parameters,
			// then this happens in Job::__construct instead.
			$title = Title::makeTitle( $params['namespace'], $params['title'] );
		} else {
			// Default title for job classes not implementing GenericParameterJob.
			// This must be a valid title because it not directly passed to
			// our Job constructor, but rather it's subclasses which may expect
			// to be able to use it.
			$title = Title::makeTitle( NS_SPECIAL, 'Blankpage' );
		}

		if ( isset( $wgJobClasses[$command] ) ) {
			$handler = $wgJobClasses[$command];

			if ( is_callable( $handler ) ) {
				$job = call_user_func( $handler, $title, $params );
			} elseif ( class_exists( $handler ) ) {
				if ( is_subclass_of( $handler, GenericParameterJob::class ) ) {
					$job = new $handler( $params );
				} else {
					$job = new $handler( $title, $params );
				}
			} else {
				$job = null;
			}

			if ( $job instanceof Job ) {
				$job->command = $command;

				return $job;
			} else {
				throw new InvalidArgumentException(
					"Could not instantiate job '$command': bad spec!"
				);
			}
		}

		throw new InvalidArgumentException( "Invalid job command '{$command}'" );
	}

	/**
	 * @stable to call
	 *
	 * @param string $command
	 * @param array|Title|null $params
	 */
	public function __construct( $command, $params = null ) {
		if ( $params instanceof Title ) {
			// Backwards compatibility for old signature ($command, $title, $params)
			$title = $params;
			$params = func_num_args() >= 3 ? func_get_arg( 2 ) : [];
		} else {
			// Newer jobs may choose to not have a top-level title (e.g. GenericParameterJob)
			$title = null;
		}

		if ( !is_array( $params ) ) {
			throw new InvalidArgumentException( '$params must be an array' );
		}

		if (
			$title &&
			!isset( $params['namespace'] ) &&
			!isset( $params['title'] )
		) {
			// When constructing this class for submitting to the queue,
			// normalise the $title arg of old job classes as part of $params.
			$params['namespace'] = $title->getNamespace();
			$params['title'] = $title->getDBkey();
		}

		$this->command = $command;
		$this->params = $params + [ 'requestId' => WebRequest::getRequestId() ];

		if ( $this->title === null ) {
			// Set this field for access via getTitle().
			$this->title = ( isset( $params['namespace'] ) && isset( $params['title'] ) )
				? Title::makeTitle( $params['namespace'], $params['title'] )
				// GenericParameterJob classes without namespace/title params
				// should not use getTitle(). Set an invalid title as placeholder.
				: Title::makeTitle( NS_SPECIAL, '' );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function hasExecutionFlag( $flag ) {
		return ( $this->executionFlags & $flag ) === $flag;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getType() {
		return $this->command;
	}

	/**
	 * @return Title
	 */
	final public function getTitle() {
		return $this->title;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @stable to override
	 * @param string|null $field Metadata field or null to get all the metadata
	 * @return mixed|null Value; null if missing
	 * @since 1.33
	 */
	public function getMetadata( $field = null ) {
		if ( $field === null ) {
			return $this->metadata;
		}

		return $this->metadata[$field] ?? null;
	}

	/**
	 * @stable to override
	 * @param string $field Key name to set the value for
	 * @param mixed $value The value to set the field for
	 * @return mixed|null The prior field value; null if missing
	 * @since 1.33
	 */
	public function setMetadata( $field, $value ) {
		$old = $this->getMetadata( $field );
		if ( $value === null ) {
			unset( $this->metadata[$field] );
		} else {
			$this->metadata[$field] = $value;
		}

		return $old;
	}

	/**
	 * @stable to override
	 * @return int|null UNIX timestamp to delay running this job until, otherwise null
	 * @since 1.22
	 */
	public function getReleaseTimestamp() {
		return isset( $this->params['jobReleaseTimestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->params['jobReleaseTimestamp'] )
			: null;
	}

	/**
	 * @return int|null UNIX timestamp of when the job was queued, or null
	 * @since 1.26
	 */
	public function getQueuedTimestamp() {
		return isset( $this->metadata['timestamp'] )
			? wfTimestampOrNull( TS_UNIX, $this->metadata['timestamp'] )
			: null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getRequestId() {
		return $this->params['requestId'] ?? null;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getReadyTimestamp() {
		return $this->getReleaseTimestamp() ?: $this->getQueuedTimestamp();
	}

	/**
	 * Whether the queue should reject insertion of this job if a duplicate exists
	 *
	 * This can be used to avoid duplicated effort or combined with delayed jobs to
	 * coalesce updates into larger batches. Claimed jobs are never treated as
	 * duplicates of new jobs, and some queues may allow a few duplicates due to
	 * network partitions and fail-over. Thus, additional locking is needed to
	 * enforce mutual exclusion if this is really needed.
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function ignoreDuplicates() {
		return $this->removeDuplicates;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function allowRetries() {
		return true;
	}

	/**
	 * @stable to override
	 * @return int
	 */
	public function workItemCount() {
		return 1;
	}

	/**
	 * Subclasses may need to override this to make duplication detection work.
	 * The resulting map conveys everything that makes the job unique. This is
	 * only checked if ignoreDuplicates() returns true, meaning that duplicate
	 * jobs are supposed to be ignored.
	 *
	 * @stable to override
	 * @return array Map of key/values
	 * @since 1.21
	 */
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
			// Queues pack and hash this array, so normalize the order
			ksort( $info['params'] );
		}

		return $info;
	}

	/**
	 * Get "root job" parameters for a task
	 *
	 * This is used to no-op redundant jobs, including child jobs of jobs,
	 * as long as the children inherit the root job parameters. When a job
	 * with root job parameters and "rootJobIsSelf" set is pushed, the
	 * deduplicateRootJob() method is automatically called on it. If the
	 * root job is only virtual and not actually pushed (e.g. the sub-jobs
	 * are inserted directly), then call deduplicateRootJob() directly.
	 *
	 * @see JobQueue::deduplicateRootJob()
	 *
	 * @param string $key A key that identifies the task
	 * @return array Map of:
	 *   - rootJobIsSelf    : true
	 *   - rootJobSignature : hash (e.g. SHA1) that identifies the task
	 *   - rootJobTimestamp : TS_MW timestamp of this instance of the task
	 * @since 1.21
	 */
	public static function newRootJobParams( $key ) {
		return [
			'rootJobIsSelf'    => true,
			'rootJobSignature' => sha1( $key ),
			'rootJobTimestamp' => wfTimestampNow()
		];
	}

	/**
	 * @stable to override
	 * @see JobQueue::deduplicateRootJob()
	 * @return array
	 * @since 1.21
	 */
	public function getRootJobParams() {
		return [
			'rootJobSignature' => $this->params['rootJobSignature'] ?? null,
			'rootJobTimestamp' => $this->params['rootJobTimestamp'] ?? null
		];
	}

	/**
	 * @stable to override
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool
	 * @since 1.22
	 */
	public function hasRootJobParams() {
		return isset( $this->params['rootJobSignature'] )
			&& isset( $this->params['rootJobTimestamp'] );
	}

	/**
	 * @stable to override
	 * @see JobQueue::deduplicateRootJob()
	 * @return bool Whether this is job is a root job
	 */
	public function isRootJob() {
		return $this->hasRootJobParams() && !empty( $this->params['rootJobIsSelf'] );
	}

	/**
	 * @param callable $callback A function with one parameter, the success status, which will be
	 *   false if the job failed or it succeeded but the DB changes could not be committed or
	 *   any deferred updates threw an exception. (This parameter was added in 1.28.)
	 * @since 1.27
	 */
	protected function addTeardownCallback( $callback ) {
		$this->teardownCallbacks[] = $callback;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function teardown( $status ) {
		foreach ( $this->teardownCallbacks as $callback ) {
			call_user_func( $callback, $status );
		}
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function toString() {
		$paramString = '';
		if ( $this->params ) {
			foreach ( $this->params as $key => $value ) {
				if ( $paramString != '' ) {
					$paramString .= ' ';
				}
				if ( is_array( $value ) ) {
					$filteredValue = [];
					foreach ( $value as $k => $v ) {
						$json = FormatJson::encode( $v );
						if ( $json === false || mb_strlen( $json ) > 512 ) {
							$filteredValue[$k] = gettype( $v ) . '(...)';
						} else {
							$filteredValue[$k] = $v;
						}
					}
					if ( count( $filteredValue ) <= 10 ) {
						$value = FormatJson::encode( $filteredValue );
					} else {
						$value = "array(" . count( $value ) . ")";
					}
				} elseif ( is_object( $value ) && !method_exists( $value, '__toString' ) ) {
					$value = "object(" . get_class( $value ) . ")";
				}

				$flatValue = (string)$value;
				if ( mb_strlen( $value ) > 1024 ) {
					$flatValue = "string(" . mb_strlen( $value ) . ")";
				}

				$paramString .= "$key={$flatValue}";
			}
		}

		$metaString = '';
		foreach ( $this->metadata as $key => $value ) {
			if ( is_scalar( $value ) && mb_strlen( $value ) < 1024 ) {
				$metaString .= ( $metaString ? ",$key=$value" : "$key=$value" );
			}
		}

		$s = $this->command;
		if ( is_object( $this->title ) ) {
			$s .= " {$this->title->getPrefixedDBkey()}";
		}
		if ( $paramString != '' ) {
			$s .= " $paramString";
		}
		if ( $metaString != '' ) {
			$s .= " ($metaString)";
		}

		return $s;
	}

	protected function setLastError( $error ) {
		$this->error = $error;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getLastError() {
		return $this->error;
	}
}
