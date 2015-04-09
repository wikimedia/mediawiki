<?php

/**
 * @since 1.26
 */
abstract class LogFormatterTestCase extends MediaWikiLangTestCase {

	public function doTestLogFormatter( $row, $extra ) {
		RequestContext::resetMain();
		$row = $this->expandDatabaseRow( $row, $this->isLegacy( $extra ) );

		$logEntry = DatabaseLogEntry::newFromRow( $row );
		$formatter = LogFormatter::newFromEntry( $logEntry );

		$this->assertEquals(
			$extra['text'],
			self::removeSomeHtml( $formatter->getActionText() ),
			'Action text is equal to expected text'
		);
		
		$vals = array();
		if ( $row['log_params'] !== '' ) {
			ApiQueryLogEvents::addLogParams(
				new ApiResult( new ApiMain() ),
				$vals,
				$logEntry->getParameters(),
				$logEntry->getType(),
				$logEntry->getSubtype(),
				$logEntry->getTimestamp(),
				$logEntry->isLegacy()
			);
		}
		
		$this->assertEquals(
			$extra['api'],
			$vals,
			'Api log params is equal to expected array'
		);
	}

	protected function isLegacy( $extra ) {
		return isset( $extra['legacy'] ) && $extra['legacy'];
	}

	protected function expandDatabaseRow( $data, $legacy ) {
		return array(
			// no log_id because no insert in database
			'log_type' => $data['type'],
			'log_action' => $data['action'],
			'log_timestamp' => isset( $data['timestamp'] ) ? $data['timestamp'] : wfTimestampNow(),
			'log_user' => isset( $data['user'] ) ? $data['user'] : 0,
			'log_user_text' => isset( $data['user_text'] ) ? $data['user_text'] : 'User',
			'log_namespace' => isset( $data['namespace'] ) ? $data['namespace'] : NS_MAIN,
			'log_title' => isset( $data['title'] ) ? $data['title'] : 'Main_Page',
			'log_page' => isset( $data['page'] ) ? $data['page'] : 0,
			'log_comment' => isset( $data['comment'] ) ? $data['comment'] : '',
			'log_params' => $legacy
				? LogPage::makeParamBlob( $data['params'] )
				: LogEntryBase::makeParamBlob( $data['params'] ),
			'log_deleted' => isset( $data['deleted'] ) ? $data['deleted'] : 0,
		);
	}

	private static function removeSomeHtml( $html ) {
		return trim( preg_replace( '/<(a|span)[^>]*>([^<]*)<\/\1>/', '$2', $html ) );
	}
}
