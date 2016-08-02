<?php

namespace MediaWiki\Storage;

/**
 * Value object representing meta-information about persistent content.
 * Instances of this class correspond roughly to rows in the content table.
 * They contain sufficient information to find, load, deserialize and interpret
 * the content blob. They do not provide access to the Content objet directly.
 *
 * Note that this value object does not hold information about which revisions the
 * content is associated with. @see SlotRecord for that.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class ContentRecord {

	/**
	 * @var string
	 */
	private $blobAddress;

	/**
	 * @var string
	 */
	private $contentModel;

	/**
	 * @var int
	 */
	private $logicalContentSize;

	/**
	 * @var string
	 */
	private $serializationFormat;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @var int
	 */
	private $origin;

	/**
	 * @param string $blobAddress
	 * @param string $contentModel
	 * @param int $logicalContentSize
	 * @param string $serializationFormat
	 * @param string $hash MUST be the has has returned by Content::getHash
	 * @param int $origin ID of the revision that originated this content
	 */
	public function __construct(
		$blobAddress,
		$contentModel,
		$logicalContentSize,
		$serializationFormat,
		$hash,
		$origin
	) {
		// FIXME: assert types!
		$this->blobAddress = $blobAddress;
		$this->contentModel = $contentModel;
		$this->logicalContentSize = $logicalContentSize;
		$this->serializationFormat = $serializationFormat;
		$this->hash = $hash;
		$this->origin = $origin;
	}

	/**
	 * @return string
	 */
	public function getBlobAddress() {
		return $this->blobAddress;
	}

	/**
	 * @return string
	 */
	public function getContentModel() {
		return $this->contentModel;
	}

	/**
	 * @return int
	 */
	public function getLogicalContentSize() {
		return $this->logicalContentSize;
	}

	/**
	 * @return string
	 */
	public function getSerializationFormat() {
		return $this->serializationFormat;
	}

	/**
	 * @return string
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * @return int the Id of the revision that originated this content
	 */
	public function getOrigin() {
		return $this->origin;
	}

}
