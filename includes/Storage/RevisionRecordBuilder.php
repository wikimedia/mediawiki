<?php
namespace MediaWiki\Storage;

use Content;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\Sql\RevisionSlotTable;
use Revision;
use User;


/**
 * A mutable value object implementing RevisionRecord.
 *
 * This can be used to construct the data needed to create a new revision,
 * and may also useful for testing.
 */
class RevisionRecordBuilder implements RevisionRecord {

	/**
	 * @var LinkTarget
	 */
	private $title;

	/**
	 * @var string
	 */
	private $comment;

	/**
	 * @var string
	 */
	private $userText;

	/**
	 * @var string
	 */
	private $timestamp;

	/**
	 * @var int|null
	 */
	private $size = null;

	/**
	 * @var string|null
	 */
	private $sha1 = null;

	/**
	 * @var int
	 */
	private $id = 0;

	/**
	 * @var int
	 */
	private $parentId = 0;

	/**
	 * @var int
	 */
	private $page = 0;

	/**
	 * @var int
	 */
	private $user = 0;

	/**
	 * @var bool
	 */
	private $minor = false;

	/**
	 * @var int see Revision::DELETED_XXX
	 */
	private $visibility = 0;

	/**
	 * @var bool
	 */
	private $current = false;

	/**
	 * @var Content[]
	 */
	private $slotContent = [];

	/**
	 * @var ContentBlobInfo[]
	 */
	private $primarySlots = [];

	/**
	 * RevisionRecordBuilder constructor.
	 *
	 * @param LinkTarget $title
	 * @param string $comment
	 * @param string $userText
	 * @param string $timestamp
	 */
	public function __construct( LinkTarget $title, $comment, $userText, $timestamp ) {
		$this->title = $title;
		$this->comment = $comment;
		$this->userText = $userText;
		$this->timestamp = $timestamp;
	}

	/**
	 * @param string $slot
	 * @param Content $content
	 */
	public function setSlotContent( $slot, Content $content ) {
		$this->size = null;
		$this->sha1 = null;

		$this->slotContent[$slot] = $content;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @param string $comment
	 */
	public function setComment( $comment ) {
		$this->comment = $comment;
	}

	/**
	 * @param string $userText
	 */
	public function setUserText( $userText ) {
		$this->userText = $userText;
	}

	/**
	 * @param string $timestamp
	 */
	public function setTimestamp( $timestamp ) {
		$this->timestamp = $timestamp;
	}

	/**
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @param int $parentId
	 */
	public function setParentId( $parentId ) {
		$this->parentId = $parentId;
	}

	/**
	 * @param int $page
	 */
	public function setPage( $page ) {
		$this->page = $page;
	}

	/**
	 * @param int $user
	 */
	public function setUser( $user ) {
		$this->user = $user;
	}

	/**
	 * @param boolean $minor
	 */
	public function setMinor( $minor ) {
		$this->minor = $minor;
	}

	/**
	 * @param int $visibility
	 */
	public function setVisibility( $visibility ) {
		$this->visibility = $visibility;
	}

	/**
	 * @param boolean $current
	 */
	public function setCurrent( $current ) {
		$this->current = $current;
	}

	/**
	 * Get revision ID
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int
	 */
	public function getParentId() {
		return $this->parentId;
	}

	/**
	 * Returns the length of the text in this revision.
	 *
	 * @return int
	 */
	public function getSize() {
		if ( $this->size === null ) {
			$this->size = Revision::calculateRevisionSize( $this->primarySlots );
		}

		return $this->size;
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * @return string
	 */
	public function getSha1() {
		if ( $this->sha1 === null ) {
			$this->sha1 = Revision::calculateRevisionHash( $this->primarySlots );
		}

		return $this->sha1;
	}

	/**
	 * Returns the title of the page associated with this entry.
	 *
	 * @return LinkTarget
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get the page ID
	 *
	 * @return int
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * Fetch revision's user id
	 *
	 * @return int
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Fetch revision's username.
	 *
	 * @return string
	 */
	public function getUserText() {
		return $this->userText;
	}

	/**
	 * Fetch revision's username.
	 *
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @return bool
	 */
	public function isMinor() {
		return $this->minor;
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		return ( $this->visibility & $field ) == $field;
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility() {
		return $this->visibility;
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return bool
	 */
	public function isCurrent() {
		return $this->current;
	}

	/**
	 * @return string[] The names of all primary slots in this revision
	 */
	public function listPrimarySlots() {
		return array_keys( $this->slotContent );
	}

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @return ContentBlobInfo
	 * @throws NotFoundException
	 */
	public function getSlotInfo( $slot ) {
		if ( !isset( $this->primarySlots[$slot] ) ) {
			if ( !isset( $this->slotContent[$slot] ) ) {
				throw new NotFoundException( 'Slot ' . $slot . ' not found in revision ' . $this->id );
			}

			$this->primarySlots[$slot] = $this->makeSlotInfo( $slot, $this->slotContent[$slot] );
		}
		return $this->primarySlots[$slot];
	}

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @return Content
	 * @throws NotFoundException
	 */
	public function getSlotContent( $slot ) {
		if ( !isset( $this->slotContent[$slot] ) ) {
			throw new NotFoundException( 'Slot ' . $slot . ' not found in revision ' . $this->id );
		}
		return $this->slotContent[$slot];
	}

	private function makeSlotInfo( $slot, Content $content ) {
		// FIXME: looks like we need a "junior" version of ContentBlobInfo
		return new ContentBlobInfo(
			'', // TODO: make sure we handle the empty address nicely everywhere
			$content->getModel(),
			$content->getSize(),
			'',
			-1,
			wfTimestampNow(),
			$content->getHash()
		);
	}

}