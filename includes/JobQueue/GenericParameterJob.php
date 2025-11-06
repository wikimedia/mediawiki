<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue;

/**
 * Interface for generic jobs only uses the parameters field and are JSON serializable.
 * Jobs using this interface require `needsPage: false` to be set
 * in the JobClasses configuration of their extension.json declaration.
 *
 * @stable to implement
 * @since 1.33
 * @ingroup JobQueue
 */
interface GenericParameterJob extends IJobSpecification {
	/**
	 * @param array $params JSON-serializable map of parameters
	 */
	public function __construct( array $params );
}

/** @deprecated class alias since 1.44 */
class_alias( GenericParameterJob::class, 'GenericParameterJob' );
