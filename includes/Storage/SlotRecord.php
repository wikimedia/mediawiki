<?php

namespace MediaWiki\Storage;

/**
 * Value object representing meta-information about a specific slot of a specific revision,
 * and the content of that slot.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SlotRecord extends ContentRecord {

	/**
	 * @var int
	 */
	private $revisionId;

	/**
	 * @var int
	 */
	private $contentId;

	/**
	 * @var string
	 */
	private $role;

	/**
	 * @param int $revisionId
	 * @param int $contentId
	 * @param string $role
	 * @param string $blobAddress
	 * @param string $contentModel
	 * @param int $logicalContentSize
	 * @param string $serializationFormat
	 * @param string $hash  MUST be the has has returned by Content::getHash
	 * @param int $origin
	 */
	public function __construct(
		$revisionId,
		$contentId,
		$role,
		$blobAddress,
		$contentModel,
		$logicalContentSize,
		$serializationFormat,
		$hash,
		$origin
	) {
		parent::__construct(
			$blobAddress,
			$contentModel,
			$logicalContentSize,
			$serializationFormat,
			$hash,
			$origin
		);

		// FIXME: assert types!
		$this->revisionId = $revisionId;
		$this->contentId = $contentId;
		$this->role = $role;
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revisionId;
	}

	/**
	 * @return int
	 */
	public function getContentId() {
		return $this->contentId;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

}
