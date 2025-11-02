<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Dump profiler data in a ".xhprof" file.
 *
 * @ingroup Profiler
 * @since 1.25
 */
class ProfilerOutputDump extends ProfilerOutput {
	/** @var string */
	protected $suffix = ".xhprof";

	/**
	 * Can this output type be used?
	 *
	 * @return bool
	 */
	public function canUse() {
		if ( empty( $this->params['outputDir'] ) ) {
			return false;
		}
		return true;
	}

	public function log( array $stats ) {
		if ( !$this->collector instanceof ProfilerXhprof ) {
			$this->logger->error( 'ProfilerOutputDump must be used with ProfilerXhprof' );
			return;
		}
		$data = $this->collector->getRawData();
		$filename = sprintf( "%s/%s.%s%s",
			$this->params['outputDir'],
			uniqid(),
			$this->collector->getProfileID(),
			$this->suffix );
		file_put_contents( $filename, serialize( $data ) );
	}
}
