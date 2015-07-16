<?php

/**
 * Allows client-side code or bot to query the status of a pending or completed
 * file transform operation.
 *
 * @file
 * @since 1.26
 */

class ApiQueryFileTransformStatus extends ApiQueryBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$pageIds = $this->getPageSet()->getAllTitlesByNamespace();
		// Make sure we have files in the title set:
		if ( !empty( $pageIds[NS_FILE] ) ) {
			$titles = array_keys( $pageIds[NS_FILE] );
			asort( $titles ); // Ensure the order is always the same

			$result = $this->getResult();
			$images = RepoGroup::singleton()->findFiles( $titles );
			/**
			 * @var $img File
			 */
			foreach ( $images as $img ) {
				$conds = array(
					'ft_img_name' => $img->getName()
				);
				if ( $params['ftid'] ) {
					$conds['ft_id'] = $params['ftid'];
				}

				$db = wfGetDB( DB_SLAVE );
				$rows = $db->select( 'file_transform',
					'*',
					$conds,
					__METHOD__,
					array( 'ORDER BY' => 'ft_id DESC' )
				);
				$statuses = array();
				foreach ( $rows as $row ) {
					$ft = FileTransform::newFromRow( $row );
					$status = array(
						'ftid' => $ft->getId(),
						'userid' => $ft->getUser()->getId(),
						'timestamp' => wfTimestamp( TS_ISO_8601, $ft->getTimestamp() ),
					);
					if ( $ft->getStartedTimestamp() !== null ) {
						$status['startedTimestamp'] = wfTimestamp( TS_ISO_8601, $ft->getStartedTimestamp() );
					}
					if ( $ft->getCompletedTimestamp() !== null ) {
						$status['completedTimestamp'] = wfTimestamp( TS_ISO_8601, $ft->getCompletedTimestamp() );
					}
					if ( $ft->getSuccess() !== null ) {
						$status['success'] = $ft->getSuccess();
					}
					if ( $ft->getError() !== null ) {
						$sttus['error'] = $ft->getError();
					}
					$statuses[] = $status;
				}
				$path = array( 'query', 'pages', $img->getTitle()->getArticleId() );
				$result->addValue( $path, 'filetransform', $statuses );
			}
		}
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function getAllowedParams() {
		return array(
			'ftid' => array(
				ApiBase::PARAM_TYPE => 'integer'
			),
		);
	}

	/**
	 * @deprecated since MediaWiki core 1.25
	 */
	public function getDescription() {
		return array(
			'Get transcode status for a given file page'
		);
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 */
	protected function getExamplesMessages() {
		return array(
			'action=query&prop=filetransformstatus&ftid=123'
				=> 'apihelp-query+filetransformstatus-example-1',
		);
	}
}
