<?php
/**
 * Portions taken from phpwiki-1.3.3.
 *
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup DifferenceEngine
 */

namespace Wikimedia\Diff;

/**
 * A pseudo-formatter that just passes along the Diff::$edits array
 * @newable
 * @ingroup DifferenceEngine
 */
class ArrayDiffFormatter extends DiffFormatter {

	/**
	 * @param Diff $diff A Diff object.
	 *
	 * @return array[] List of associative arrays, each describing a difference.
	 * @suppress PhanParamSignatureMismatch
	 */
	public function format( $diff ) {
		$oldline = 1;
		$newline = 1;
		$retval = [];
		foreach ( $diff->getEdits() as $edit ) {
			switch ( $edit->getType() ) {
				case 'add':
					foreach ( $edit->getClosing() as $line ) {
						$retval[] = [
							'action' => 'add',
							'new' => $line,
							'newline' => $newline++
						];
					}
					break;
				case 'delete':
					foreach ( $edit->getOrig() as $line ) {
						$retval[] = [
							'action' => 'delete',
							'old' => $line,
							'oldline' => $oldline++,
						];
					}
					break;
				case 'change':
					foreach ( $edit->getOrig() as $key => $line ) {
						$retval[] = [
							'action' => 'change',
							'old' => $line,
							'new' => $edit->getClosing( $key ),
							'oldline' => $oldline++,
							'newline' => $newline++,
						];
					}
					break;
				case 'copy':
					$oldline += $edit->norig();
					$newline += $edit->norig();
			}
		}

		return $retval;
	}

}

/** @deprecated class alias since 1.41 */
class_alias( ArrayDiffFormatter::class, 'ArrayDiffFormatter' );
