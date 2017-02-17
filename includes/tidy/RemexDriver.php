<?php

namespace MediaWiki\Tidy;

use RemexHtml\Serializer\Serializer;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\TreeBuilder\Dispatcher;
use RemexHtml\TreeBuilder\TreeBuilder;

class RemexDriver extends TidyDriverBase {
	public function __construct( array $config ) {
		parent::__construct( $config );
	}

	public function tidy( $text ) {
		$formatter = new RemexCompatFormatter;
		$serializer = new Serializer( $formatter );
		$munger = new RemexCompatMunger( $serializer );
		$treeBuilder = new TreeBuilder( $munger, [
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
