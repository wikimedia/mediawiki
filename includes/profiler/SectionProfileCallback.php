<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
use Wikimedia\ScopedCallback;

/**
 * Subclass ScopedCallback to avoid call_user_func_array(), which is slow.
 *
 * @ingroup Profiler
 * @internal For use by SectionProfiler
 */
class SectionProfileCallback extends ScopedCallback {

	protected SectionProfiler $profiler;
	protected string $section;

	public function __construct( SectionProfiler $profiler, string $section ) {
		parent::__construct( null );
		$this->profiler = $profiler;
		$this->section = $section;
	}

	public function __destruct() {
		$this->profiler->profileOutInternal( $this->section );
	}
}
