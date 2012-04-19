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
		'lang'     => 'Language',
		'output'   => 'OutputPage',
		'request'  => 'WebRequest',
//		'skin'     => 'Skin',
		'title'    => 'Title',
		'user'     => 'User',
//		'wikipage' => 'WikiPage',
	);

	/**
	 * Gives out an object factory that accepts string
	 */
	protected $classStringFactory = array(
		'lang'  => 'factory',
		'title' => 'newFromText',
		'user'  => 'newFromName',
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
			$this->setupObject( $param, $data );
		}
	}

	protected function setupObject( $parameter_name, $data ) {
		$class = $this->mapParamClass[$parameter_name];
		$setter = "set{$parameter_name}";

		if( !isset($data) ) {
			# Use a very dumb object
			$this->$setter( new $class );
		} else {
			if( is_string( $data ) ) {
				$factory = $this->classStringFactory[$parameter_name];

				$this->$setter( $class::$factory( $data ) );
			} elseif( is_object( $data ) ) {
				# Simply pass the given object
				$this->$setter( $data );
			} else {
				throw new MWException( __METHOD__ .
					" given unsupported '$parameter_name' parameter"
				);
			}
		}
	}

}
