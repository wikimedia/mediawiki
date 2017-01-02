<?php

class UploadAvatar extends UploadFromFile {
	public $mExtension;

	function createThumbnail( $imageSrc, $imageInfo, $imgDest, $thumbWidth ) {
		global $wgUseImageMagick, $wgImageMagickConvertCommand;

		if ( $wgUseImageMagick ) { // ImageMagick is enabled
			list( $origWidth, $origHeight, $typeCode ) = $imageInfo;

			if ( $origWidth < $thumbWidth ) {
				$thumbWidth = $origWidth;
			}
			$thumbHeight = ( $thumbWidth * $origHeight / $origWidth );
			$border = ' -bordercolor white  -border  0x';
			if ( $thumbHeight < $thumbWidth ) {
				$border = ' -bordercolor white  -border  0x' . ( ( $thumbWidth - $thumbHeight ) / 2 );
			}
			if ( $typeCode == 2 ) {
				exec(
					$wgImageMagickConvertCommand . ' -size ' . $thumbWidth . 'x' . $thumbWidth .
					' -resize ' . $thumbWidth . ' -crop ' . $thumbWidth . 'x' .
					$thumbWidth . '+0+0   -quality 100 ' . $border . ' ' .
					$imageSrc . ' ' . $this->avatarUploadDirectory . '/' . $imgDest . '.jpg'
				);
			}
			if ( $typeCode == 1 ) {
				exec(
					$wgImageMagickConvertCommand . ' -size ' . $thumbWidth . 'x' . $thumbWidth .
					' -resize ' . $thumbWidth . ' -crop ' . $thumbWidth . 'x' .
					$thumbWidth . '+0+0 ' . $imageSrc . ' ' . $border . ' ' .
					$this->avatarUploadDirectory . '/' . $imgDest . '.gif'
				);
			}
			if ( $typeCode == 3 ) {
				exec(
					$wgImageMagickConvertCommand . ' -size ' . $thumbWidth . 'x' . $thumbWidth .
					' -resize ' . $thumbWidth . ' -crop ' . $thumbWidth . 'x' .
					$thumbWidth . '+0+0 ' . $imageSrc . ' ' .
					$this->avatarUploadDirectory . '/' . $imgDest . '.png'
				);
			}
			if ( $typeCode == 4 ) {
				exec(
					$wgImageMagickConvertCommand . ' -size ' . $thumbWidth . 'x' . $thumbWidth .
					' -resize ' . $thumbWidth . ' -crop ' . $thumbWidth . 'x' .
					$thumbWidth . '+0+0 ' . $imageSrc . ' ' .
					$this->avatarUploadDirectory . '/' . $imgDest . '.svg'
				);
			}
		} else { // ImageMagick is not enabled, so fall back to PHP's GD library
			// Get the image size, used in calculations later.
			list( $origWidth, $origHeight, $typeCode ) = getimagesize( $imageSrc );

			switch( $typeCode ) {
				case '1':
					$fullImage = imagecreatefromgif( $imageSrc );
					$ext = 'gif';
					break;
				case '2':
					$fullImage = imagecreatefromjpeg( $imageSrc );
					$ext = 'jpg';
					break;
				case '3':
					$fullImage = imagecreatefrompng( $imageSrc );
					$ext = 'png';
					break;
				case '4':
					$fullImage = imagecreatefrompng( $imageSrc );
					$ext = 'svg';
					break;
			}

			$scale = ( $thumbWidth / $origWidth );

			// Create our thumbnail size, so we can resize to this, and save it.
			$tnImage = imagecreatetruecolor(
				$origWidth * $scale,
				$origHeight * $scale
			);

			// Resize the image.
			imagecopyresampled(
				$tnImage,
				$fullImage,
				0, 0, 0, 0,
				$origWidth * $scale,
				$origHeight * $scale,
				$origWidth,
				$origHeight
			);

			// Create a new image thumbnail.
			if ( $typeCode == 1 ) {
				imagegif( $tnImage, $imageSrc );
			} elseif ( $typeCode == 2 ) {
				imagejpeg( $tnImage, $imageSrc );
			} elseif ( $typeCode == 3 ) {
				imagepng( $tnImage, $imageSrc );
			}

			// Clean up.
			imagedestroy( $fullImage );
			imagedestroy( $tnImage );

			// Copy the thumb
			copy(
				$imageSrc,
				$this->avatarUploadDirectory . '/' . $imgDest . '.' . $ext
			);
		}
	}

	/**
	 * Create the thumbnails and delete old files
	 */
	public function performUpload( $comment, $pageText, $watch, $user, $tags = [] ) {
		global $wgUploadDirectory, $wgAvatarKey, $wgMemc;

		$this->avatarUploadDirectory = $wgUploadDirectory . '/avatars';

		$imageInfo = getimagesize( $this->mTempPath );
		if ( empty( $imageInfo[2] ) ) {
			return Status::newFatal( 'empty-file' );
		}

		switch ( $imageInfo[2] ) {
			case 1:
				$ext = 'gif';
				break;
			case 2:
				$ext = 'jpg';
				break;
			case 3:
				$ext = 'png';
				break;
			case 4:
				$ext = 'svg';
				break;
			default:
				return Status::newFatal( 'filetype-banned' );
		}

		$dest = $this->avatarUploadDirectory;

		$uid = $user->getId();
		$avatar = new wAvatar( $uid, 'l' );
		// If this is the user's first custom avatar, update statistics (in
		// case if we want to give out some points to the user for uploading
		// their first avatar)
		if ( strpos( $avatar->getAvatarImage(), 'default_' ) !== false ) {
			$stats = new UserStatsTrack( $uid, $user->getName() );
			$stats->incStatField( 'user_image' );
		}

		$this->createThumbnail( $this->mTempPath, $imageInfo, $wgAvatarKey . '_' . $uid . '_l', 75 );
		$this->createThumbnail( $this->mTempPath, $imageInfo, $wgAvatarKey . '_' . $uid . '_ml', 50 );
		$this->createThumbnail( $this->mTempPath, $imageInfo, $wgAvatarKey . '_' . $uid . '_m', 30 );
		$this->createThumbnail( $this->mTempPath, $imageInfo, $wgAvatarKey . '_' . $uid . '_s', 16 );

		if ( $ext != 'jpg' ) {
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.jpg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.jpg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.jpg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.jpg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.jpg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.jpg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.jpg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.jpg' );
			}
		}
		if ( $ext != 'gif' ) {
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.gif' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.gif' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.gif' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.gif' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.gif' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.gif' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.gif' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.gif' );
			}
		}
		if ( $ext != 'png' ) {
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.png' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.png' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.png' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.png' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.png' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.png' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.png' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.png' );
			}
		}

		if ( $ext != 'svg' ) {
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.svg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_s.svg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.svg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_m.svg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.svg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_l.svg' );
			}
			if ( is_file( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.svg' ) ) {
				unlink( $this->avatarUploadDirectory . '/' . $wgAvatarKey . '_' . $uid . '_ml.svg' );
			}
		}

		$key = wfMemcKey( 'user', 'profile', 'avatar', $uid, 's' );
		$data = $wgMemc->delete( $key );

		$key = wfMemcKey( 'user', 'profile', 'avatar', $uid, 'm' );
		$data = $wgMemc->delete( $key );

		$key = wfMemcKey( 'user', 'profile', 'avatar', $uid , 'l' );
		$data = $wgMemc->delete( $key );

		$key = wfMemcKey( 'user', 'profile', 'avatar', $uid, 'ml' );
		$data = $wgMemc->delete( $key );

		$this->mExtension = $ext;
		return Status::newGood();
	}

	/**
	 * Don't verify the upload, since it all dangerous stuff is killed by
	 * making thumbnails
	 */
	public function verifyUpload() {
		return array( 'status' => self::OK );
	}

	/**
	 * Only needed for the redirect; needs fixage
	 */
	public function getTitle() {
		return Title::makeTitle( NS_FILE, 'Avatar-placeholder' . uniqid() . '.jpg' );
	}

	/**
	 * We don't overwrite stuff, so don't care
	 */
	public function checkWarnings() {
		return array();
	}
}
