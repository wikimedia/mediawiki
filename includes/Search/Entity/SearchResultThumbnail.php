<?php

namespace MediaWiki\Search\Entity;

/**
 * Class that stores information about thumbnail, e. g. url, width and height
 * @newable
 */
class SearchResultThumbnail {
	/**
	 * Internet mime type for the representation, like "image/png" or "audio/mp3"
	 * @var string
	 */
	private $mimeType;

	/**
	 * Size of the representation in bytes or null if not applicable
	 * @var int|callable|null
	 */
	private $size;

	/**
	 * Duration of the representation in seconds or null if not applicable
	 * @var int|null
	 */
	private $duration;

	/**
	 * Full URL to the contents of the file
	 * @var string
	 */
	private $url;

	/**
	 * Width of the representation in pixels or null if not applicable
	 * @var int|null
	 */
	private $width;

	/**
	 * Height of the representation in pixels or null if not applicable
	 * @var int|null
	 */
	private $height;

	/**
	 * String that represent file identity in storage or null
	 * @var string|null
	 */
	private $name;

	/**
	 * @param string $mimeType Internet mime type for the representation,
	 * like "image/png" or "audio/mp3"
	 * @param int|callable|null $size Size of the representation in bytes.
	 *   This parameter has been deprecated in 1.41 and will be removed.
	 * @param int|null $width Width of the representation in pixels or null if not applicable
	 * @param int|null $height Height of the representation in pixels or null if not applicable
	 * @param int|null $duration Duration of the representation in seconds or
	 * null if not applicable
	 * @param string $url full URL to the contents of the file
	 * @param string|null $name full URL to the contents of the file
	 */
	public function __construct(
		string $mimeType,
		/* int|callable|null */ $size,
		?int $width,
		?int $height,
		?int $duration,
		string $url,
		?string $name
	) {
		$this->mimeType = $mimeType;
		if ( $size !== null ) {
			wfDeprecated(
				__METHOD__ . ': Passing $size is discouraged for performance reasons.',
				'1.41'
			);
		}
		$this->width = $width;
		$this->height = $height;
		$this->duration = $duration;
		$this->url = $url;
		$this->name = $name;
	}

	/**
	 * Full URL to the contents of the file
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * Width of the representation in pixels or null if not applicable
	 * @return int|null
	 */
	public function getWidth(): ?int {
		return $this->width;
	}

	/**
	 * Height of the representation in pixels or null if not applicable
	 * @return int|null
	 */
	public function getHeight(): ?int {
		return $this->height;
	}

	/**
	 * Internet mime type for the representation, like "image/png" or "audio/mp3"
	 * @return string
	 */
	public function getMimeType(): string {
		return $this->mimeType;
	}

	/**
	 * @deprecated since 1.41, Do not use, resource intensive and thus
	 *   degrade performance.
	 *
	 * Size of the representation in bytes or null if not applicable
	 * @return int|null
	 */
	public function getSize(): ?int {
		if ( is_callable( $this->size ) ) {
			$this->size = ( $this->size )();
		}

		return $this->size;
	}

	/**
	 * Duration of the representation in seconds or null if not applicable
	 * @return int|null
	 */
	public function getDuration(): ?int {
		return $this->duration;
	}

	/**
	 * String that represent file identity in storage or null
	 * @return string|null
	 */
	public function getName(): ?string {
		return $this->name;
	}
}
