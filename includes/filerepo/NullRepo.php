<?php
/**
 * File repository with no files.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * File repository with no files, for performance testing
 * @ingroup FileRepo
 */
class NullRepo extends FileRepo {
	function __construct( $info ) {}

	function storeBatch( array $triplets, $flags = 0 ) {
		return false;
	}
	function storeTemp( $originalName, $srcPath ) {
		return false;
	}
	function publishBatch( array $triplets, $flags = 0 ) {
		return false;
	}
	function deleteBatch( array $sourceDestPairs ) {
		return false;
	}
	function cleanupDeletedBatch( array $storageKeys ) {
		return false;
	}
	function fileExistsBatch( array $files, $flags = 0 ) {
		return false;
	}
	function getFileProps( $virtualUrl ) {
		return false;
	}
	function newFile( $title, $time = false ) {
		return false;
	}
	function findFile( $title, $options = array() ) {
		return false;
	}
	function concatenate( array $fileList, $targetPath, $flags = 0 ) {
		return false;
	}
}
