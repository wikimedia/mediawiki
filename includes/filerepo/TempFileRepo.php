<?php
/**
 * FileRepo for temporary files created via FileRepo::getTempRepo()
 */
class TempFileRepo extends FileRepo {
	public function getTempRepo() {
		throw new MWException( "Cannot get a temp repo from a temp repo." );
	}
}
