<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue;

/**
 * Interface for serializable objects that describe a job queue task
 *
 * A job specification can be inserted into a queue via JobQueue::push().
 * The specification parameters should be JSON serializable (e.g. no PHP classes).
 * Whatever queue the job specification is pushed into is assumed to have job runners
 * that will eventually pop the job specification from the queue, construct a RunnableJob
 * instance from the specification, and then execute that instance via RunnableJob::run().
 *
 * Job classes must have a constructor that takes a Title and a parameter array, except
 * when they also implement GenericParameterJob in which case they must only take an array.
 * When reconstructing the job from the job queue, the value returned from getParams() will
 * be passed in as the constructor's array parameter; the title will be constructed from
 * the parameter array's `namespace` and `title` fields (when these are omitted, some
 * fallback title will be used).
 *
 * @ingroup JobQueue
 * @since 1.23
 */
interface IJobSpecification {
	/**
	 * @return string Job type that defines what sort of changes this job makes
	 */
	public function getType();

	/**
	 * @return array Parameters that specify sources, targets, and options for execution
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
}

/** @deprecated class alias since 1.44 */
class_alias( IJobSpecification::class, 'IJobSpecification' );
