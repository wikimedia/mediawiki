<?php
/**
 * Backward compatibility code for MW < 1.11
 *
 * @file
 */

/**
 * Backwards compatibility class
 *
 * @deprecated. Will be removed in 1.18!
 * @ingroup FileRepo
 */
class Image extends LocalFile {
	function __construct( $title ) {
		wfDeprecated( __METHOD__ );
		$repo = RepoGroup::singleton()->getLocalRepo();
		parent::__construct( $title, $repo );
	}

	/**
	 * Wrapper for wfFindFile(), for backwards-compatibility only
	 * Do not use in core code.
	 * @deprecated
	 */
	static function newFromTitle( $title, $repo, $time = null ) {
		wfDeprecated( __METHOD__ );
		$img = wfFindFile( $title, array( 'time' => $time ) );
		if ( !$img ) {
			$img = wfLocalFile( $title );
		}
		return $img;
	}

	/**
	 * Wrapper for wfFindFile(), for backwards-compatibility only.
	 * Do not use in core code.
	 *
	 * @param $name String: name of the image, used to create a title object using Title::makeTitleSafe
	 * @return image object or null if invalid title
	 * @deprecated
	 */
	static function newFromName( $name ) {
		wfDeprecated( __METHOD__ );
		$title = Title::makeTitleSafe( NS_FILE, $name );
		if ( is_object( $title ) ) {
			$img = wfFindFile( $title );
			if ( !$img ) {
				$img = wfLocalFile( $title );
			}
			return $img;
		} else {
			return null;
		}
	}

	/**
	 * Return the URL of an image, provided its name.
	 *
	 * Backwards-compatibility for extensions.
	 * Note that fromSharedDirectory will only use the shared path for files
	 * that actually exist there now, and will return local paths otherwise.
	 *
	 * @param $name String: name of the image, without the leading "Image:"
	 * @param $fromSharedDirectory Boolean: Should this be in $wgSharedUploadPath?
	 * @return string URL of $name image
	 * @deprecated
	 */
	static function imageUrl( $name, $fromSharedDirectory = false ) {
		wfDeprecated( __METHOD__ );
		$image = null;
		if( $fromSharedDirectory ) {
			$image = wfFindFile( $name );
		}
		if( !$image ) {
			$image = wfLocalFile( $name );
		}
		return $image->getUrl();
	}
}
