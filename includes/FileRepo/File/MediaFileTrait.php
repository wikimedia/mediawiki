<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserIdentity;

/**
 * Trait for functionality related to media files
 *
 * @internal
 * @ingroup FileRepo
 */
trait MediaFileTrait {
	/**
	 * @param File $file
	 * @param Authority $performer for permissions check
	 * @param array $transforms array of transforms to include in the response
	 * @return array response data
	 */
	private function getFileInfo( $file, Authority $performer, $transforms ) {
		$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
		// If there is a problem with the file, there is very little info we can reliably
		// return (T228286, T239213), but we do what we can (T201205).
		$responseFile = [
			'title' => $file->getTitle()->getText(),
			'file_description_url' => $urlUtils->expand( $file->getDescriptionUrl(), PROTO_RELATIVE ),
			'latest' => null,
			'preferred' => null,
			'original' => null,
		];

		foreach ( $transforms as $transformType => $_ ) {
			$responseFile[$transformType] = null;
		}

		if ( $file->exists() ) {
			$uploader = $file->getUploader( File::FOR_THIS_USER, $performer );
			if ( $uploader ) {
				$fileUser = [
					'id' => $uploader->getId(),
					'name' => $uploader->getName(),
				];
			} else {
				$fileUser = [
					'id' => null,
					'name' => null,
				];
			}
			$responseFile['latest'] = [
				'timestamp' => wfTimestamp( TS_ISO_8601, $file->getTimestamp() ),
				'user' => $fileUser,
			];

			// If the file doesn't and shouldn't have a duration, return null instead of 0.
			// Testing for 0 first, then checking mediatype, makes gifs behave as desired for
			// both still and animated cases.
			$duration = $file->getLength();
			$mediaTypesWithDurations = [ MEDIATYPE_AUDIO, MEDIATYPE_VIDEO, MEDIATYPE_MULTIMEDIA ];
			if ( $duration == 0 && !in_array( $file->getMediaType(), $mediaTypesWithDurations ) ) {
				$duration = null;
			}

			if ( $file->allowInlineDisplay() ) {
				foreach ( $transforms as $transformType => $transform ) {
					$responseFile[$transformType] = $this->getTransformInfo(
						$file,
						// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
						$duration,
						$transform['maxWidth'],
						$transform['maxHeight']
					);
				}
			}

			$responseFile['original'] = [
				'mediatype' => $file->getMediaType(),
				'size' => $file->getSize(),
				'width' => $file->getWidth() ?: null,
				'height' => $file->getHeight() ?: null,
				'duration' => $duration,
				'url' => $urlUtils->expand( $file->getUrl(), PROTO_RELATIVE ),
			];
		}

		return $responseFile;
	}

	/**
	 * @param File $file
	 * @param int|null $duration File duration (if any)
	 * @param int $maxWidth Max width to display at
	 * @param int $maxHeight Max height to display at
	 * @return array|null Transform info ready to include in response, or null if unavailable
	 */
	private function getTransformInfo( $file, $duration, $maxWidth, $maxHeight ) {
		$transformInfo = null;

		[ $width, $height ] = $file->getDisplayWidthHeight( $maxWidth, $maxHeight );
		$transform = $file->transform( [ 'width' => $width, 'height' => $height ] );
		if ( $transform && !$transform->isError() ) {
			// $file->getSize() returns original size. Only include if dimensions match.
			$size = null;
			if ( $file->getWidth() == $transform->getWidth() &&
				$file->getHeight() == $transform->getHeight()
			) {
				$size = $file->getSize();
			}

			$transformInfo = [
				'mediatype' => $transform->getFile()->getMediaType(),
				'size' => $size,
				'width' => $transform->getWidth() ?: null,
				'height' => $transform->getHeight() ?: null,
				'duration' => $duration,
				'url' => MediaWikiServices::getInstance()->getUrlUtils()
					->expand( $transform->getUrl(), PROTO_RELATIVE ),
			];
		}

		return $transformInfo;
	}

	/**
	 * Returns the corresponding $wgImageLimits entry for the selected user option
	 *
	 * @param UserIdentity $user
	 * @param string $optionName Name of a option to check, typically imagesize or thumbsize
	 * @return int[]
	 * @since 1.35
	 */
	public static function getImageLimitsFromOption( UserIdentity $user, string $optionName ) {
		$imageLimits = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ImageLimits );
		$optionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		$option = $optionsLookup->getIntOption( $user, $optionName );
		if ( !isset( $imageLimits[$option] ) ) {
			$option = $optionsLookup->getDefaultOption( $optionName, $user );
		}

		// The user offset might still be incorrect, specially if
		// $wgImageLimits got changed (see T10858).
		if ( !isset( $imageLimits[$option] ) ) {
			// Default to the first offset in $wgImageLimits
			$option = 0;
		}

		// if nothing is set, fallback to a hardcoded default
		return $imageLimits[$option] ?? [ 800, 600 ];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( MediaFileTrait::class, 'MediaFileTrait' );
