<?php

namespace MediaWiki\Tidy;

use RemexHtml\Serializer\Serializer;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;
use RemexHtml\TreeBuilder\TreeMutationTracer;

class RemexDriver extends TidyDriverBase {
	private $trace = false;

	public function __construct( array $config ) {
		$this->trace = !empty( $config['treeMutationTrace'] );
		parent::__construct( $config );
	}

	public function tidy( $text ) {
		$formatter = new RemexCompatFormatter;
		$serializer = new Serializer( $formatter );
		$munger = new RemexCompatMunger( $serializer );
		if ( $this->trace ) {
			$handler = new TreeMutationTracer( $munger, function ( $msg ) {
				wfDebug( "RemexHtml: $msg" );
			} );
		} else {
			$handler = $munger;
		}
		$treeBuilder = new TreeBuilder( $handler, [
			'ignoreErrors' => true,
			'ignoreNulls' => true,
		] );
		$dispatcher = new Dispatcher( $treeBuilder );
		$tokenizer = new Tokenizer( $dispatcher, $text, [
			'ignoreErrors' => true,
			'ignoreCharRefs' => true,
			'ignoreNulls' => true,
			'skipPreprocess' => true,
		] );
		$tokenizer->execute( [
			'fragmentNamespace' => \RemexHtml\HTMLData::NS_HTML,
			'fragmentName' => 'body'
		] );
		return $serializer->getResult();
	}
}
