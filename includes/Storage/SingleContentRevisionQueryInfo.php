<?php

namespace MediaWiki\Storage;

use CommentStore;
use stdClass;

trait SingleContentRevisionQueryInfo {

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new revision object.
	 *
	 * MCR migration note: this replaces Revision::getQueryInfo
	 *
	 * @since 1.31
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'page': Join with the page table, and select fields to identify the page
	 *  - 'user': Join with the user table, and select the user name
	 *  - 'text': Join with the text table, and select fields to load page text
	 *  - 'useContentHandler':
	 *
	 * @return array With three keys:
	 *  - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *  - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *  - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getQueryInfo( $options = [] ) {
		$ret = [
			'tables' => [],
			'fields' => [],
			'joins'  => [],
		];

		$ret['tables'][] = 'revision';
		$ret['fields'] = array_merge( $ret['fields'], [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		] );

		$commentQuery = CommentStore::newKey( 'rev_comment' )->getJoin();
		$ret['tables'] = array_merge( $ret['tables'], $commentQuery['tables'] );
		$ret['fields'] = array_merge( $ret['fields'], $commentQuery['fields'] );
		$ret['joins'] = array_merge( $ret['joins'], $commentQuery['joins'] );

		if ( in_array( 'useContentHandler', $options, true ) ) {
			$ret['fields'][] = 'rev_content_format';
			$ret['fields'][] = 'rev_content_model';
		}

		if ( in_array( 'page', $options, true ) ) {
			$ret['tables'][] = 'page';
			$ret['fields'] = array_merge( $ret['fields'], [
				'page_namespace',
				'page_title',
				'page_id',
				'page_latest',
				'page_is_redirect',
				'page_len',
			] );
			$ret['joins']['page'] = [ 'INNER JOIN', [ 'page_id = rev_page' ] ];
		}

		if ( in_array( 'user', $options, true ) ) {
			$ret['tables'][] = 'user';
			$ret['fields'] = array_merge( $ret['fields'], [
				'user_name',
			] );
			$ret['joins']['user'] = [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ];
		}

		if ( in_array( 'text', $options, true ) ) {
			$ret['tables'][] = 'text';
			$ret['fields'] = array_merge( $ret['fields'], [
				'old_text',
				'old_flags'
			] );
			$ret['joins']['text'] = [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ];
		}

		return $ret;
	}

	/**
	 * Return the tables, fields, and join conditions to be selected to create
	 * a new archived revision object.
	 *
	 * MCR migration note: this replaces Revision::getArchiveQueryInfo
	 *
	 * @since 1.31
	 *
	 * @param array $options Any combination of the following strings
	 *  - 'useContentHandler':
	 *
	 * @return array With three keys:
	 *   - tables: (string[]) to include in the `$table` to `IDatabase->select()`
	 *   - fields: (string[]) to include in the `$vars` to `IDatabase->select()`
	 *   - joins: (array) to include in the `$join_conds` to `IDatabase->select()`
	 */
	public function getArchiveQueryInfo( $options = [] ) {
		$commentQuery = CommentStore::newKey( 'ar_comment' )->getJoin();
		$ret = [
			'tables' => [ 'archive' ] + $commentQuery['tables'],
			'fields' => [
							'ar_id',
							'ar_page_id',
							'ar_namespace',
							'ar_title',
							'ar_rev_id',
							'ar_text',
							'ar_text_id',
							'ar_timestamp',
							'ar_user_text',
							'ar_user',
							'ar_minor_edit',
							'ar_deleted',
							'ar_len',
							'ar_parent_id',
							'ar_sha1',
						] + $commentQuery['fields'],
			'joins' => $commentQuery['joins'],
		];

		if ( in_array( 'useContentHandler', $options, true ) ) {
			$ret['fields'][] = 'ar_content_format';
			$ret['fields'][] = 'ar_content_model';
		}

		return $ret;
	}

	/**
	 * Maps fields of the archive row to corresponding revision rows.
	 *
	 * @param object $archiveRow
	 *
	 * @return object a revision row object, corresponding to $archiveRow.
	 */
	public function mapArchiveFields( $archiveRow ) {
		$fieldMap = [
			// keep with ar prefix:
			'ar_id'        => 'ar_id',

			// not the same suffix:
			'ar_page_id'        => 'rev_page',
			'ar_rev_id'         => 'rev_id',

			// same suffix:
			'ar_text_id'        => 'rev_text_id',
			'ar_timestamp'      => 'rev_timestamp',
			'ar_user_text'      => 'rev_user_text',
			'ar_user'           => 'rev_user',
			'ar_minor_edit'     => 'rev_minor_edit',
			'ar_deleted'        => 'rev_deleted',
			'ar_len'            => 'rev_len',
			'ar_parent_id'      => 'rev_parent_id',
			'ar_sha1'           => 'rev_sha1',
			'ar_comment'        => 'rev_comment',
			'ar_comment_cid'    => 'rev_comment_cid',
			'ar_comment_id'     => 'rev_comment_id',
			'ar_comment_text'   => 'rev_comment_text',
			'ar_comment_data'   => 'rev_comment_data',
			'ar_comment_old'    => 'rev_comment_old',
			'ar_content_format' => 'rev_content_format',
			'ar_content_model'  => 'rev_content_model',
		];

		if ( empty( $archiveRow->ar_text_id ) ) {
			$fieldMap['ar_text'] = 'old_text';
			$fieldMap['ar_flags'] = 'old_flags';
		}

		$revRow = new stdClass();
		foreach ( $fieldMap as $arKey => $revKey ) {
			if ( property_exists( $archiveRow, $arKey ) ) {
				$revRow->$revKey = $archiveRow->$arKey;
			}
		}

		return $revRow;
	}

}
