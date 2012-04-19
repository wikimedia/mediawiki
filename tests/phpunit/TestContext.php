<?php
/**
 * Helper around RequestContext.
 *
 * This let you easily build a context by passing an array of Title, User,
 * Output etc at construction time, save up all the setFoobar() calls one
 * has to do after creating a fresh context instance.
 *
 * @par Example with strings as values:
 * @code
 * $context = new TestContest( array(
 *  'title' => 'Main_page',
 *  'user'  => 'hashar',
 *  'skin'  => 'monobook',
 *  'lang'  => 'fr',
 * ) );
 * // Do something with $context :-]
 * @endcode
 *
 * @par Also support objects:
 * @code
 * $title = Title::newFromName( 'foobar' );
 * $context = new TestContext( array(
 *  'title' => $title,
 * ) );
 * @endcode
 */

class TestContext extends RequestContext {

	# 'parameter name' => 'MediaWiki Class'
	protected $mapParamClass = array(
		'lang'     => 'Lang',
		'output'   => 'OutputPage',
		'request'  => 'WebRequest',
		'skin'     => 'Skin',
		'title'    => 'Title',
		'user'     => 'User',
//		'wikipage' => 'WikiPage',
	);


	/**
	 * Easily create a new context using conventions!
	 */
	function __construct() {
		$parameters = func_get_args();
		if( $parameters ) {
			$parameters = $parameters[0];
		} else {
			$parameters = array();
		}

		$this->normalize( $parameters );
		$this->checkParams( $parameters );
		$this->setupFromParams( $parameters );
	}

	function normalize( &$parameters ) {
		if( count( $parameters ) ) {
			$normalized_keys = array_map( 'strtolower', array_keys( $parameters ) );
			$parameters = array_combine( $normalized_keys, array_values( $parameters ) );
		}
	}

	function checkParams( $parameters ) {
		// First pass verify keys are supported
		$delta = array_diff_key( $parameters, $this->mapParamClass );
		if( !empty( $delta ) ) {
			throw new MWException( __METHOD__ .
				" given unsupported parameter(s): " . join( ', ', array_keys( $delta ) )
			);
		}
	}

	protected function setupFromParams( $parameters ) {

		# Make sure all possible parameters are a key to trigger
		# all mappers, they might react to an empty / null value
		$all_keys = array_fill_keys(
			array_flip( $this->mapParamClass )
			, null
		);
		$parameters += $all_keys;

		# Now call internal mappers
		foreach( $parameters as $param => $data ) {
			call_user_func(
				array( $this, "setup{$param}" )
				, $data
			);
		}
	}

	protected function setupLang( $possibleLang ) { }
	protected function setupOutput( $possibleOutput) { }
	protected function setupRequest( $possibleRequest) { }
	protected function setupSkin( $possibleSkin ) { }

	protected function setupTitle( $possibleTitle ) {
		# Set title
		if( isset( $possibleTitle ) ) {
			if( is_string( $possibleTitle ) ) {
				$this->setTitle( Title::newFromText( $possibleTitle ) );
			} elseif( $possibleTitle instanceof Title ) {
				$this->setTitle( $possibleTitle );
			} else {
				throw new MWException( __METHOD__ .
					" given unsupported [title] parameter"
				);
			}
		} else {
			// Fallback to a very dumb title :-D
			$this->setTitle( new Title() );
		}
	}

	protected function setupUser( $possibleUser ) { }

}
