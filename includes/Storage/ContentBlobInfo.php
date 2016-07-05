<?php

namespace MediaWiki\Storage;

/**
 *
 * @todo: is this a concrete slot for a given reivsion id?
 * @todo: do we allow direct access to Content from here? Could be a lazy loading container.
 *
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
		$this->blobSize = $blobSize;
		$this->contentModel = $contentModel;
		$this->logicalContentSize = $logicalContentSize;
		$this->serializationFormat = $serializationFormat;
		$this->hash = $hash;
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
