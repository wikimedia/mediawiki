<?php

namespace MediaWiki\FileRepo;

use LogicException;

/**
 * FileRepo for temporary files created by FileRepo::getTempRepo()
 *
 * @internal
 * @ingroup FileRepo
 */
class TempFileRepo extends FileRepo {
	public function getTempRepo(): never {
		throw new LogicException( "Cannot get a temp repo from a temp repo." );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( TempFileRepo::class, 'TempFileRepo' );
