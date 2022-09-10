<?php
/**
 * FileRepo for temporary files created by FileRepo::getTempRepo()
 *
 * @internal
 * @ingroup FileRepo
 */
class TempFileRepo extends FileRepo {
	public function getTempRepo() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		throw new MWException( "Cannot get a temp repo from a temp repo." );
	}
}
