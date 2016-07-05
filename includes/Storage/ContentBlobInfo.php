<?php

namespace MediaWiki\Storage;

/**
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class ContentBlobInfo {

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
	private $sha1;

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
	 * @param string $sha1
	 */
	public function __construct(
		$blobAddress,
		$contentModel,
		$logicalContentSize,
		$serializationFormat,
		$blobSize,
		$timestamp,
		$sha1
	) {

		$this->blobAddress = $blobAddress;
		$this->blobSize = $blobSize;
		$this->contentModel = $contentModel;
		$this->logicalContentSize = $logicalContentSize;
		$this->serializationFormat = $serializationFormat;
		$this->sha1 = $sha1;
		$this->timestamp = $timestamp;
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
	public function getSha1() {
		return $this->sha1;
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

}
