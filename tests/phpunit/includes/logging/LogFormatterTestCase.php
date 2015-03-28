<?php

class LogFormatterTestCase extends MediaWikiLangTestCase {

	public function doTestLogFormatter( $row, $extra ) {
		$row = $this->expandDatabaseRow( $row, $this->isLegacy( $extra ) );

		$logEntry = DatabaseLogEntry::newFromRow( $row );
		$formatter = LogFormatter::newFromEntry( $logEntry );

		// Make LogFormatter::getActionMessage public for testing and call it
		$reflectionMethod = new ReflectionMethod( 'LogFormatter', 'getActionMessage' );
		$reflectionMethod->setAccessible( true );
		$actionMsg = $reflectionMethod->invoke( $formatter );

		$this->assertEquals(
			$extra['key'],
			$actionMsg->getKey(),
			'LogFormatter chooses the same message key as expected'
		);
		$this->assertEquals(
			$extra['paramCount'],
			count( $actionMsg->getParams() ),
			'LogFormatter gives the same count of message parameters as expected'
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
}
