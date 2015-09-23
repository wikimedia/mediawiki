<?php

namespace MediaWiki\Tidy;
use MWHttpRequest;
use Exception;

class Html5Depurate extends TidyDriverBase {
	public function __construct( array $config ) {
		parent::__construct( $config + array(
			'url' => 'http://localhost:4339/document',
			'timeout' => 10,
			'connectTimeout' => 0.5,
		) );
	}

	public function tidy( $text ) {
		$wrappedtext = '<!DOCTYPE html><html>' .
			'<body>' . $text . '</body></html>';

		$req = MWHttpRequest::factory( $this->config['url'],
			array(
				'method' => 'POST',
				'timeout' => $this->config['timeout'],
				'connectTimeout' => $this->config['connectTimeout'],
				'postData' => array(
					'text' => $wrappedtext
				)
			) );
		$status = $req->execute();
		if ( !$status->isOK() ) {
			throw new Exception( "Error contacting depurate service: " . $status->getWikiText() );
		} elseif ( $req->getStatus() !== 200 ) {
			throw new Exception( "Depurate returned error: " . $status->getWikiText() );
		}
		$result = $req->getContent();
		$startBody = strpos( $result, "<body>" );
		$endBody = strrpos( $result, "</body>" );
		if ( $startBody !== false && $endBody !== false && $endBody > $startBody ) {
			$startBody += strlen( "<body>" );
			return substr( $result, $startBody, $endBody - $startBody );
		} else {
			return $text . "\n<!-- Html5Depurate returned an invalid result -->";
		}
	}
}
