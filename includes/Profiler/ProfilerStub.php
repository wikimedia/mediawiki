<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Profiler;

/**
 * Stub profiler that does nothing.
 *
 * @ingroup Profiler
 */
class ProfilerStub extends Profiler {
	/** @inheritDoc */
	#[\NoDiscard]
	public function scopedProfileIn( $section ): ?SectionProfileCallback {
		return null; // no-op
	}

	/** @inheritDoc */
	public function getFunctionStats() {
		return [];
	}

	/** @inheritDoc */
	public function getOutput() {
		return '';
	}

	public function close() {
	}

	public function logData() {
	}

	public function logDataPageOutputOnly() {
	}
}

/** @deprecated class alias since 1.46 */
class_alias( ProfilerStub::class, 'ProfilerStub' );
