<?php

use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\Title\Title;

require_once __DIR__ . '/../includes/Benchmarker.php';

class BenchmarkCommentFormatter extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark CommentFormatter::format()' );
		$this->addOption( 'file', 'A JSON API result from list=recentchanges',
			false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file',
			__DIR__ . '/data/CommentFormatter/rc100-2021-07-29.json' );
		$json = file_get_contents( $file );
		if ( !$json ) {
			$this->fatalError( "Unable to read input file \"$file\"" );
		}
		$result = json_decode( $json, true );
		if ( !isset( $result['query']['recentchanges'] ) ) {
			$this->fatalError( "Invalid JSON data" );
		}
		$entries = $result['query']['recentchanges'];
		$inputs = [];
		$comments = [];
		foreach ( $entries as $entry ) {
			$inputs[] = [
				'comment' => $entry['comment'],
				'title' => Title::newFromText( $entry['title'] )
			];
			$comments[] = $entry['comment'];
		}
		$this->bench( [
			'CommentFormatter::format' => [
				'function' => function () use ( $inputs ) {
					Title::clearCaches();
					$formatter = $this->getServiceContainer()->getCommentFormatter();
					foreach ( $inputs as $input ) {
						$formatter->format(
							$input['comment'],
							$input['title']
						);
					}
				},
			],

			'CommentFormatter::createBatch' => [
				'function' => function () use ( $inputs ) {
					Title::clearCaches();
					$formatter = $this->getServiceContainer()->getCommentFormatter();
					$comments = [];
					foreach ( $inputs as $input ) {
						$comments[] = ( new CommentItem( $input['comment'] ) )
							->selfLinkTarget( $input['title'] );
					}
					$formatter->createBatch()
						->comments( $comments )
						->execute();
				}
			],

			'CommentFormatter::formatStrings' => [
				'function' => function () use ( $comments ) {
					Title::clearCaches();
					$formatter = $this->getServiceContainer()->getCommentFormatter();
					$formatter->formatStrings( $comments );
				}
			],

		] );
	}
}

$maintClass = BenchmarkCommentFormatter::class;
require_once RUN_MAINTENANCE_IF_MAIN;
