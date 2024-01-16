<?php
/**
 * FileRepo for temporary files created by FileRepo::getTempRepo()
 *
 * @internal
 * @ingroup FileRepo
 */
class TempFileRepo extends FileRepo {
	/**
	 * @return never
	 */
	public function getTempRepo() {
		throw new LogicException( "Cannot get a temp repo from a temp repo." );
	}
}
