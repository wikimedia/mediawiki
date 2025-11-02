<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\ExternalUserNames;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;

/**
 * Represents a revision, log entry or upload during the import process.
 * This class sticks closely to the structure of the XML dump.
 *
 * @since 1.2
 *
 * @ingroup SpecialPage
 */
class WikiRevision implements ImportableUploadRevision, ImportableOldRevision {

	/**
	 * @since 1.2
	 * @var Title
	 */
	public $title = null;

	/**
	 * @since 1.6.4
	 * @var int
	 */
	public $id = 0;

	/**
	 * @since 1.2
	 * @var string TS_MW timestamp, a string with 14 digits
	 */
	public $timestamp = "20010115000000";

	/**
	 * @since 1.2
	 * @var string
	 */
	public $user_text = "";

	/**
	 * @deprecated since 1.39, use {@see $user_text} instead
	 * @since 1.27
	 * @var User|null
	 */
	public $userObj = null;

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use getContent
	 * @var string
	 */
	public $model = null;

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use getContent
	 * @var string
	 */
	public $format = null;

	/**
	 * @since 1.2
	 * @deprecated since 1.35, use getContent
	 * @var string
	 */
	public $text = "";

	/**
	 * @since 1.12.2
	 * @var int
	 */
	protected $size;

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use getContent
	 * @var Content
	 */
	public $content = null;

	/**
	 * @since 1.24
	 * @var ContentHandler
	 */
	protected $contentHandler = null;

	/**
	 * @since 1.2.6
	 * @var string
	 */
	public $comment = "";

	private MutableRevisionSlots $slots;

	/**
	 * @since 1.5.7
	 * @var bool
	 */
	public $minor = false;

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $type = "";

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $action = "";

	/**
	 * @since 1.12.2
	 * @var string
	 */
	public $params = "";

	/**
	 * @since 1.17
	 * @var string
	 */
	public $fileSrc = '';

	/**
	 * @var string|null
	 */
	private $sha1base36;

	/**
	 * @since 1.34
	 * @var string[]
	 */
	protected $tags = [];

	/**
	 * @since 1.17
	 * @var string
	 */
	public $archiveName = '';

	/**
	 * @since 1.12.2
	 * @var string|null
	 */
	protected $filename;

	/**
	 * @since 1.12.2
	 * @var string|null
	 */
	protected $src = null;

	/**
	 * @since 1.18
	 * @var bool
	 * @todo Unused?
	 */
	public $isTemp = false;

	/** @var bool */
	private $mNoUpdates = false;

	public function __construct() {
		$this->slots = new MutableRevisionSlots();
	}

	public function setTitle( Title $title ) {
		$this->title = $title;
	}

	/**
	 * @since 1.6.4
	 * @param int $id
	 */
	public function setID( $id ) {
		$this->id = $id;
	}

	/**
	 * @since 1.2
	 * @param string $ts
	 */
	public function setTimestamp( $ts ) {
		# 2003-08-05T18:30:02Z
		$this->timestamp = wfTimestamp( TS_MW, $ts );
	}

	/**
	 * @since 1.2
	 * @param string $user
	 */
	public function setUsername( $user ) {
		$this->user_text = $user;
	}

	/**
	 * @deprecated since 1.39, use {@see setUsername} instead
	 * @since 1.27
	 * @param User $user
	 */
	public function setUserObj( $user ) {
		// Not officially supported, but some callers pass false from e.g. User::newFromName()
		$this->userObj = $user ?: null;
		if ( $this->user_text === '' && $user ) {
			$this->user_text = $user->getName();
		}
	}

	/**
	 * @deprecated since 1.39, use {@see setUsername} instead, it does the same anyway
	 * @since 1.2
	 * @param string $ip
	 */
	public function setUserIP( $ip ) {
		$this->user_text = $ip;
	}

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use setContent instead.
	 * @param string $model
	 */
	public function setModel( $model ) {
		$this->model = $model;
	}

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use setContent instead.
	 * @param string $format
	 */
	public function setFormat( $format ) {
		$this->format = $format;
	}

	/**
	 * @since 1.2
	 * @deprecated since 1.35, use setContent instead.
	 * @param string $text
	 */
	public function setText( $text ) {
		$handler = $this->getContentHandler();
		$content = $handler->unserializeContent( $text );
		$this->setContent( SlotRecord::MAIN, $content );
	}

	/**
	 * @since 1.35
	 * @param string $role
	 * @param Content $content
	 */
	public function setContent( $role, $content ) {
		$this->slots->setContent( $role, $content );

		// backwards compat
		if ( $role === SlotRecord::MAIN ) {
			$this->content = $content;
			$this->model = $content->getModel();
			$this->format = $content->getDefaultFormat();
			$this->text = $content->serialize();
		}
	}

	/**
	 * @since 1.2.6
	 * @param string $text
	 */
	public function setComment( string $text ) {
		$this->comment = $text;
	}

	/**
	 * @since 1.5.7
	 * @param bool $minor
	 */
	public function setMinor( $minor ) {
		$this->minor = (bool)$minor;
	}

	/**
	 * @since 1.12.2
	 * @param string|null $src
	 */
	public function setSrc( $src ) {
		$this->src = $src;
	}

	/**
	 * @since 1.17
	 * @param string $src
	 * @param bool $isTemp
	 */
	public function setFileSrc( $src, $isTemp ) {
		$this->fileSrc = $src;
		$this->isTemp = $isTemp;
	}

	/**
	 * @since 1.17
	 * @param string $sha1base36
	 */
	public function setSha1Base36( $sha1base36 ) {
		$this->sha1base36 = $sha1base36 ?: null;
	}

	/**
	 * @since 1.34
	 * @param string[] $tags
	 */
	public function setTags( array $tags ) {
		$this->tags = $tags;
	}

	/**
	 * @since 1.12.2
	 * @param string $filename
	 */
	public function setFilename( $filename ) {
		$this->filename = $filename;
	}

	/**
	 * @since 1.17
	 * @param string $archiveName
	 */
	public function setArchiveName( $archiveName ) {
		$this->archiveName = $archiveName;
	}

	/**
	 * @since 1.12.2
	 * @param int $size
	 */
	public function setSize( $size ) {
		$this->size = intval( $size );
	}

	/**
	 * @since 1.12.2
	 * @param string $type
	 */
	public function setType( $type ) {
		$this->type = $type;
	}

	/**
	 * @since 1.12.2
	 * @param string $action
	 */
	public function setAction( $action ) {
		$this->action = $action;
	}

	/**
	 * @since 1.12.2
	 * @param string $params
	 */
	public function setParams( $params ) {
		$this->params = $params;
	}

	/**
	 * @since 1.18
	 * @param bool $noupdates
	 */
	public function setNoUpdates( $noupdates ) {
		$this->mNoUpdates = $noupdates;
	}

	/**
	 * @since 1.2
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @since 1.6.4
	 * @return int
	 */
	public function getID() {
		return $this->id;
	}

	/**
	 * @since 1.2
	 * @return string TS_MW timestamp, a string with 14 digits
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @since 1.2
	 * @return string
	 */
	public function getUser() {
		return $this->user_text;
	}

	/**
	 * @deprecated since 1.39, use {@see getUser} instead; this is almost always null anyway
	 * @since 1.27
	 * @return User|null Typically null, use {@see getUser} instead
	 */
	public function getUserObj() {
		return $this->userObj;
	}

	/**
	 * @since 1.2
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @since 1.24
	 * @deprecated since 1.35, use getContent
	 * @return ContentHandler
	 * @throws MWUnknownContentModelException
	 */
	public function getContentHandler() {
		$this->contentHandler ??= MediaWikiServices::getInstance()
			->getContentHandlerFactory()
			->getContentHandler( $this->getModel() );

		return $this->contentHandler;
	}

	/**
	 * @since 1.21
	 * @param string $role added in 1.35
	 * @return Content
	 */
	public function getContent( $role = SlotRecord::MAIN ) {
		return $this->slots->getContent( $role );
	}

	/**
	 * @since 1.35
	 * @param string $role
	 * @return SlotRecord
	 */
	public function getSlot( $role ) {
		return $this->slots->getSlot( $role );
	}

	/**
	 * @since 1.35
	 * @return string[]
	 */
	public function getSlotRoles() {
		return $this->slots->getSlotRoles();
	}

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use getContent
	 * @return string
	 */
	public function getModel() {
		$this->model ??= $this->getTitle()->getContentModel();

		return $this->model;
	}

	/**
	 * @since 1.21
	 * @deprecated since 1.35, use getContent
	 * @return string
	 */
	public function getFormat() {
		$this->format ??= $this->getContentHandler()->getDefaultFormat();

		return $this->format;
	}

	/**
	 * @since 1.2.6
	 * @return string
	 */
	public function getComment(): string {
		return $this->comment;
	}

	/**
	 * @since 1.5.7
	 * @return bool
	 */
	public function getMinor() {
		return $this->minor;
	}

	/**
	 * @since 1.12.2
	 * @return string|null
	 */
	public function getSrc() {
		return $this->src;
	}

	/**
	 * @since 1.17
	 * @return string|false
	 */
	public function getSha1() {
		if ( $this->sha1base36 ) {
			return Wikimedia\base_convert( $this->sha1base36, 36, 16 );
		}
		return false;
	}

	/**
	 * @since 1.31
	 * @return string|false
	 */
	public function getSha1Base36() {
		return $this->sha1base36 ?? false;
	}

	/**
	 * @since 1.34
	 * @return string[]
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @since 1.17
	 * @return string
	 */
	public function getFileSrc() {
		return $this->fileSrc;
	}

	/**
	 * @since 1.17
	 * @return bool
	 */
	public function isTempSrc() {
		return $this->isTemp;
	}

	/**
	 * @since 1.12.2
	 * @return mixed
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @since 1.17
	 * @return string
	 */
	public function getArchiveName() {
		return $this->archiveName;
	}

	/**
	 * @since 1.12.2
	 * @return mixed
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @since 1.12.2
	 * @return string
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * @since 1.4.1
	 * @deprecated since 1.31. Use OldRevisionImporter::import
	 * @return bool
	 */
	public function importOldRevision() {
		if ( $this->mNoUpdates ) {
			$importer = MediaWikiServices::getInstance()->getWikiRevisionOldRevisionImporterNoUpdates();
		} else {
			$importer = MediaWikiServices::getInstance()->getWikiRevisionOldRevisionImporter();
		}
		return $importer->import( $this );
	}

	/**
	 * @since 1.12.2
	 * @return bool
	 */
	public function importLogItem() {
		$services = MediaWikiServices::getInstance();
		$dbw = $services->getConnectionProvider()->getPrimaryDatabase();

		$userName = $this->getUser();
		if ( ExternalUserNames::isExternal( $userName ) ) {
			// Use newAnonymous() since the user name is already prefixed.
			$user = UserIdentityValue::newAnonymous( $userName );
		} else {
			$user = $this->getUserObj() ?: User::newFromName( $userName, false );
		}

		# @todo FIXME: This will not record autoblocks
		if ( !$this->getTitle() ) {
			wfDebug( __METHOD__ . ": skipping invalid {$this->type}/{$this->action} log time, timestamp " .
				$this->timestamp );
			return false;
		}
		# Check if it exists already
		// @todo FIXME: Use original log ID (better for backups)
		$prior = (bool)$dbw->newSelectQueryBuilder()
			->select( '1' )
			->from( 'logging' )
			->where( [
				'log_type' => $this->getType(),
				'log_action' => $this->getAction(),
				'log_timestamp' => $dbw->timestamp( $this->timestamp ),
				'log_namespace' => $this->getTitle()->getNamespace(),
				'log_title' => $this->getTitle()->getDBkey(),
				'log_params' => $this->params
			] )
			->caller( __METHOD__ )->fetchField();
		// @todo FIXME: This could fail slightly for multiple matches :P
		if ( $prior ) {
			wfDebug( __METHOD__
				. ": skipping existing item for Log:{$this->type}/{$this->action}, timestamp "
				. $this->timestamp );
			return false;
		}
		$actorId = $services->getActorNormalization()->acquireActorId( $user, $dbw );
		$dbw->newInsertQueryBuilder()
			->insertInto( 'logging' )
			->row( [
				'log_type' => $this->type,
				'log_action' => $this->action,
				'log_timestamp' => $dbw->timestamp( $this->timestamp ),
				'log_actor' => $actorId,
				'log_namespace' => $this->getTitle()->getNamespace(),
				'log_title' => $this->getTitle()->getDBkey(),
				'log_params' => $this->params
				] + $services->getCommentStore()->insert( $dbw, 'log_comment', $this->getComment() ) )
			->caller( __METHOD__ )->execute();
		return true;
	}

}
