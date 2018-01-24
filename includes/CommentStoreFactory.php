<?php

/**
 * @since 1.31
 */
class CommentStoreFactory {

	/** @var Language Language to use for comment truncation */
	private $lang;

	/** @var int One of the MIGRATION_* constants */
	private $migrationStage;

	/**
	 * @param Language $lang to use for comment truncation
	 * @param int $migrationStage One of the MIGRATION_* constants
	 */
	public function __construct( $lang, $migrationStage ) {
		$this->lang = $lang;
		$this->migrationStage = $migrationStage;
	}

	/**
	 * @since 1.31
	 *
	 * @param string $key A key such as "rev_comment" identifying the comment
	 *  field being fetched.
	 * @return CommentStore
	 */
	public function newForKey( $key ) {
		return new CommentStore( $key, $this->lang, $this->migrationStage );
	}

}
