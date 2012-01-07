<?php
/**
 * A repository for files accessible via the local filesystem.
 *
 * @file
 * @ingroup FileRepo
 */

/**
 * A repository for files accessible via the local filesystem.
 * Does not support database access or registration.
 * 
 * This is a mostly a legacy class. New uses should not be added.
 * 
 * @ingroup FileRepo
 * @deprecated since 1.19
 */
class FSRepo extends FileRepo {
	function __construct( array $info ) {
		if ( !isset( $info['backend'] ) ) {
			// B/C settings...
			$directory = $info['directory'];
			$deletedDir = isset( $info['deletedDir'] )
				? $info['deletedDir']
				: false;
			$thumbDir = isset( $info['thumbDir'] )
				? $info['thumbDir']
				: "{$directory}/thumb";
			$fileMode = isset( $info['fileMode'] )
				? $info['fileMode']
				: 0644;

			$repoName = $info['name'];
			// Get the FS backend configuration
			$backend = new FSFileBackend( array(
				'name'           => $info['name'] . '-backend',
				'lockManager'    => 'fsLockManager',
				'containerPaths' => array(
					"{$repoName}-public"  => "{$directory}",
					"{$repoName}-temp"    => "{$directory}/temp",
					"{$repoName}-thumb"   => $thumbDir,
					"{$repoName}-deleted" => $deletedDir
				),
				'fileMode'       => $fileMode,
			) );
			// Update repo config to use this backend
			$info['backend'] = $backend;
		}

		parent::__construct( $info );

		if ( !( $this->backend instanceof FSFileBackend ) ) {
			throw new MWException( "FSRepo only supports FSFileBackend." );
		}
	}
}
