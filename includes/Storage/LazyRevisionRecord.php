<?php
namespace MediaWiki\Storage;

use Content;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\Sql\RevisionSlotTable;
use Revision;
use User;


/**
 * A RevisionRecord implementation based on the old Revision object, plus slot handling.
 *
 * @note This class currently has a circular dependency with Revision. it intended as a stepping
 * stone. Eventually, the relevant logic from Revision should be migrated here, and the reference
 * to the Revision object can be removed.
 *
 * @todo Better name?? ClassicRevisionRecored? RevisionRevisionRecord?...
 *
 * longer needed.
 */
class LazyRevisionRecord implements RevisionRecord {
	/**
	 * @var RevisionContentLookup
	 */
	private $revisionContentLookup;
	/**
	 * @var Revision
	 */
	private $revision;
	/**
	 * @var int
	 */
	private $audience;
	/**
	 * @var null|User
	 */
	private $user;

	/**
	 * LazyRevisionRecord constructor.
	 *
	 * @param RevisionContentLookup $revisionContentLookup
	 * @param Revision $revision (circular dependency for now!)
	 * @param int $audience
	 * @param User|null $user
	 */
	public function __construct(
		RevisionContentLookup $revisionContentLookup,
		Revision $revision,
		$audience = Revision::FOR_PUBLIC,
		User $user = null
	) {
		$this->revisionContentLookup = $revisionContentLookup;
		$this->revision = $revision;

		// TODO: do audience checks in a wrapper/proxy
		$this->audience = $audience;
		$this->user = $user;
	}

	/**
	 * Get revision ID
	 *
	 * @return int
	 */
	public function getId() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getId();
	}

	/**
	 * Get parent revision ID (the original previous page revision)
	 *
	 * @return int
	 */
	public function getParentId() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getParentId();
	}

	/**
	 * Returns the length of the text in this revision.
	 *
	 * @return int
	 */
	public function getSize() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getSize();
	}

	/**
	 * Returns the base36 sha1 of the text in this revision.
	 *
	 * @return string
	 */
	public function getSha1() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getSha1();
	}

	/**
	 * Returns the title of the page associated with this entry.
	 *
	 * @return LinkTarget
	 */
	public function getTitle() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getTitle();
	}

	/**
	 * Get the page ID
	 *
	 * @return int
	 */
	public function getPage() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getPage();
	}

	/**
	 * @param mixed $value The value to check
	 * @param string|int $target The name of the suppressed data ("comment", "content", "user", etc)
	 *
	 * @throws SuppressedDataException if $value is null
	 * @return mixed $value if not null
	 */
	private function throwSuppressedDataExceptionIfNull( $value, $target ) {
		if ( $value === null ) {
			throw new SuppressedDataException( $target, $this->audience );
		}
		return $value;
	}

	/**
	 * Fetch revision's user id
	 *
	 * @return int
	 */
	public function getUser() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->throwSuppressedDataExceptionIfNull(
			$this->revision->getUser( $this->audience, $this->user ),
			Revision::DELETED_USER
		);
	}

	/**
	 * Fetch revision's username.
	 *
	 * @return string
	 */
	public function getUserText() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->throwSuppressedDataExceptionIfNull(
			$this->revision->getUserText( $this->audience, $this->user ),
			Revision::DELETED_USER
		);
	}

	/**
	 * Fetch revision's username.
	 *
	 * @return string
	 */
	public function getComment() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->throwSuppressedDataExceptionIfNull(
			$this->revision->getComment( $this->audience, $this->user ),
			Revision::DELETED_COMMENT
		);
	}

	/**
	 * @return bool
	 */
	public function isMinor() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->isMinor();
	}

	/**
	 * @param int $field One of DELETED_* bitfield constants
	 *
	 * @return bool
	 */
	public function isDeleted( $field ) {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->isDeleted( $field );
	}

	/**
	 * Get the deletion bitfield of the revision
	 *
	 * @return int
	 */
	public function getVisibility() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getVisibility();
	}

	/**
	 * @return string
	 */
	public function getTimestamp() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->getTimestamp();
	}

	/**
	 * @return bool
	 */
	public function isCurrent() {
		// TODO: turn this around, so Revision asks RevisionRecord
		return $this->revision->isCurrent();
	}

	/**
	 * @return string[] The names of all primary slots in this revision
	 */
	public function listPrimarySlots() {
		// TODO: cache slot list / slot info
		// TODO: consider moving more logic from RevisionLookup into RevisionRecord
		return $this->revisionContentLookup->getRevisionSlots( $this->getId() );
	}

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @return ContentBlobInfo
	 * @throws NotFoundException
	 */
	public function getSlotInfo( $slot ) {
		// TODO: cache slot info
		return $this->revisionContentLookup->getRevisionInfo( $this->getId(), $slot );
	}

	/**
	 * @param string $slot the name of the desired slot
	 *
	 * @return Content
	 * @throws NotFoundException
	 */
	public function getSlotContent( $slot ) {
		if ( $this->audience == Revision::FOR_PUBLIC
				&& $this->isDeleted( Revision::DELETED_TEXT )
		) {
			throw new SuppressedDataException( Revision::DELETED_TEXT, $this->audience );
		} elseif ( $this->audience == Revision::FOR_THIS_USER
				&& !$this->revision->userCan( Revision::DELETED_TEXT, $this->user )
		) {
			throw new SuppressedDataException( Revision::DELETED_TEXT, $this->audience );
		}

		// TODO: cache slot content
		return $this->revisionContentLookup->getRevisionContent( $this->getId(), $slot );
	}

}