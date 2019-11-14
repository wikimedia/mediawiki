<?php

use MediaWiki\Linker\LinkTarget;

/**
 * @since 1.26
 */
abstract class LogFormatterTestCase extends MediaWikiLangTestCase {

	public function doTestLogFormatter( $row, $extra, $userGroups = [] ) {
		RequestContext::resetMain();
		$row = $this->expandDatabaseRow( $row, $this->isLegacy( $extra ) );

		$context = new RequestContext();
		$context->setUser( $this->getTestUser( $userGroups )->getUser() );

		$formatter = LogFormatter::newFromRow( $row );
		$formatter->setContext( $context );

		$this->assertEquals(
			$extra['text'],
			self::removeSomeHtml( $formatter->getActionText() ),
			'Action text is equal to expected text'
		);

		$this->assertSame( // ensure types and array key order
			$extra['api'],
			self::removeApiMetaData( $formatter->formatParametersForApi() ),
			'Api log params is equal to expected array'
		);

		if ( isset( $extra['preload'] ) ) {
			$this->assertArrayEquals(
				$this->getLinkTargetsAsStrings( $extra['preload'] ),
				$this->getLinkTargetsAsStrings(
					$formatter->getPreloadTitles()
				)
			);
		}
	}

	private function getLinkTargetsAsStrings( array $linkTargets ) {
		return array_map( function ( LinkTarget $t ) {
			return $t->getInterwiki() . ':' . $t->getNamespace() . ':'
				. $t->getDBkey() . '#' . $t->getFragment();
		}, $linkTargets );
	}

	protected function isLegacy( $extra ) {
		return isset( $extra['legacy'] ) && $extra['legacy'];
	}

	protected function expandDatabaseRow( $data, $legacy ) {
		return [
			// no log_id because no insert in database
			'log_type' => $data['type'],
			'log_action' => $data['action'],
			'log_timestamp' => $data['timestamp'] ?? wfTimestampNow(),
			'log_user' => $data['user'] ?? 0,
			'log_user_text' => $data['user_text'] ?? 'User',
			'log_actor' => $data['actor'] ?? 0,
			'log_namespace' => $data['namespace'] ?? NS_MAIN,
			'log_title' => $data['title'] ?? 'Main_Page',
			'log_page' => $data['page'] ?? 0,
			'log_comment_text' => $data['comment'] ?? '',
			'log_comment_data' => null,
			'log_params' => $legacy
				? LogPage::makeParamBlob( $data['params'] )
				: LogEntryBase::makeParamBlob( $data['params'] ),
			'log_deleted' => $data['deleted'] ?? 0,
		];
	}

	private static function removeSomeHtml( $html ) {
		$html = str_replace( '&quot;', '"', $html );
		$html = preg_replace( '/\xE2\x80[\x8E\x8F]/', '', $html ); // Strip lrm/rlm
		return trim( strip_tags( $html ) );
	}

	private static function removeApiMetaData( $val ) {
		if ( is_array( $val ) ) {
			unset( $val['_element'] );
			unset( $val['_type'] );
			foreach ( $val as $key => $value ) {
				$val[$key] = self::removeApiMetaData( $value );
			}
		}
		return $val;
	}
}
