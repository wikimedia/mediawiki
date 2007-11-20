<?php

require_once( dirname( __FILE__ ). '/../maintenance/commandLine.inc' );

$wgHooks['BeforeParserFetchTemplateAndtitle'][] = 'PPFuzzTester::templateHook';

class PPFuzzTester {
	var $hairs = array(
		'[[', ']]', '{{', '}}', '{{{', '}}}', 
		'<', '>', '<nowiki', '<gallery', '</nowiki>', '</gallery>', '<nOwIkI>', '</NoWiKi>',
		//'<!--' , '-->',
		//'<ref>', '</ref>', '<references/>',
		"\n==", "==\n",
		'|', '=', "\n", ' ', "\t", "\x7f",
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 
		'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
	);
	var $minLength = 0;
	var $maxLength = 20;
	var $maxTemplates = 5;
	var $outputTypes = array( 'OT_HTML', 'OT_WIKI', 'OT_MSG', 'OT_PREPROCESS' );
	static $currentTest = false;

	function execute() {
		if ( !file_exists( 'results' ) ) {
			mkdir( 'results' );
		}
		if ( !is_dir( 'results' ) ) {
			echo "Unable to create 'results' directory\n";
			exit( 1 );
		}
		for ( $i = 0; true; $i++ ) {
			try {
				self::$currentTest = new PPFuzzTest( $this );
				self::$currentTest->execute();
			} catch ( MWException $e ) {
				$testReport = self::$currentTest->getReport();
				$exceptionReport = $e->getText();
				$hash = md5( $testReport );
				file_put_contents( "results/ppft-$hash.in", serialize( self::$currentTest ) );
				file_put_contents( "results/ppft-$hash.fail", 
					"Input:\n$testReport\n\nException report:\n$exceptionReport\n" );
				print "Test $hash failed\n";
			}
			if ( $i % 1000 == 0 ) {
				print "$i tests done\n";
				/*
				$testReport = self::$currentTest->getReport();
				$filename = 'results/ppft-' . md5( $testReport ) . '.pass';
				file_put_contents( $filename, "Input:\n$testReport\n" );*/
			}
		}
	}

	function makeInputText() {
		$length = mt_rand( $this->minLength, $this->maxLength );
		$s = '';
		for ( $i = 0; $i < $length; $i++ ) {
			$hairIndex = mt_rand( 0, count( $this->hairs ) - 1 );
			$s .= $this->hairs[$hairIndex];
		}
		// Send through the UTF-8 normaliser
		// This resolves a few differences between the old preprocessor and the 
		// XML-based one, which doesn't like illegals and converts line endings.
		// It's done by the MW UI, so it's a reasonably legitimate thing to do.
		$s = UtfNormal::cleanUp( $s );
		return $s;
	}

	function makeTitle() {
		return Title::newFromText( mt_rand( 0, 1000000 ), mt_rand( 0, 10 ) );
	}

	function pickOutputType() {
		$count = count( $this->outputTypes );
		return $this->outputTypes[ mt_rand( 0, $count - 1 ) ];
	}
}

class PPFuzzTest {
	var $templates, $mainText, $title;

	function __construct( $tester ) {
		$this->parent = $tester;
		$this->mainText = $tester->makeInputText();
		$this->title = $tester->makeTitle();
		$this->outputType = $tester->pickOutputType();
		$this->templates = array();
	}

	function templateHook( $title ) {
		$titleText = $title->getPrefixedDBkey();
				
		if ( !isset( $this->templates[$titleText] ) ) {
			$finalTitle = $title;
			if ( count( $this->templates ) >= $this->parent->maxTemplates ) {
				// Too many templates
				$text = false;
			} else {
				if ( !mt_rand( 0, 1 ) ) {
					// Redirect
					$finalTitle = $this->parent->makeTitle();
				}
				if ( !mt_rand( 0, 5 ) ) {
					// Doesn't exist
					$text = false;
				} else {
					$text = $this->parent->makeInputText();
				}
			}
			$this->templates[$titleText] = array(
				'text' => $text,
				'finalTitle' => $finalTitle );
		}
		return $this->templates[$titleText];
	}

	function execute() {
		global $wgParser;
		$options = new ParserOptions;
		$options->setTemplateCallback( array( $this, 'templateHook' ) );
		$wgParser->startExternalParse( $this->title, $options, constant( $this->outputType ) );
		return $wgParser->srvus( $this->mainText );
	}

	function getReport() {
		$s = "Title: " . $this->title->getPrefixedDBkey() . "\n" .
			"Output type: {$this->outputType}\n" . 
			"Main text: " . var_export( $this->mainText, true ) . "\n";
		foreach ( $this->templates as $titleText => $template ) {
			$finalTitle = $template['finalTitle'];
			if ( $finalTitle != $titleText ) {
				$s .= "[[$titleText]] -> [[$finalTitle]]: " . var_export( $template['text'], true ) . "\n";
			} else {
				$s .= "[[$titleText]]: " . var_export( $template['text'], true ) . "\n";
			}
		}
		return $s;
	}
}

ini_set( 'memory_limit', '50M' );
if ( isset( $args[0] ) ) {
	$testText = file_get_contents( $args[0] );
	if ( !$testText ) {
		print "File not found\n";
		exit( 1 );
	}
	$test = unserialize( $testText );
	print $test->getReport();
	$result = $test->execute();
	print "Test passed.\nResult: $result\n";
} else {
	$tester = new PPFuzzTester;
	$tester->execute();
}
