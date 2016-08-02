<?php

namespace MediaWiki\Storage;

/**
 * Value object representing meta-information about some content of some revision(s).
 * Instances of this class correspond roughly to rows in the content table.
 *
 * Note that this value object does not hold information about which revisions the
 * content is associated with, not does it provide access to the content's data blob
 * or the deserialized Content object. For this, @see RevisionContentStore.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class RevisionContentInfo {

	/**
	 * @var string
	 */
	private $blobAddress;

	/**
	 * @var int
	 */
	private $blobSize;

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
	 * @var string
	 */
	private $timestamp;

	/**
	 * @param string $blobAddress
	 * @param string $contentModel
	 * @param int $logicalContentSize
	 * @param string $serializationFormat
	 * @param int $blobSize
	 * @param string $timestamp
	 * @param string $hash  MUST be the has has returned by Content::getHash
	 */
	public function __construct(
		$blobAddress,
		$contentModel,
		$logicalContentSize,
		$serializationFormat,
		$blobSize,
		$timestamp,
		$hash
	) {

		$this->blobAddress = $blobAddress;
		$this->contentModel = $contentModel;
		$this->logicalContentSize = $logicalContentSize;
		$this->serializationFormat = $serializationFormat;
		$this->blobSize = $blobSize;
		$this->timestamp = $timestamp;
		$this->hash = $hash;
	}

	/**
	 * @return string
	 */
	public function getBlobAddress() {
		return $this->blobAddress;
	}

	/**
	 * @return int
	 */
	public function getBlobSize() {
		return $this->blobSize;
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
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

}
