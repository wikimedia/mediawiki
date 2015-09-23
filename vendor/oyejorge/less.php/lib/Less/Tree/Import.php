<?php

/**
 * CSS @import node
 *
 * The general strategy here is that we don't want to wait
 * for the parsing to be completed, before we start importing
 * the file. That's because in the context of a browser,
 * most of the time will be spent waiting for the server to respond.
 *
 * On creation, we push the import path to our import queue, though
 * `import,push`, we also pass it a callback, which it'll call once
 * the file has been fetched, and parsed.
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Import extends Less_Tree{

	public $options;
	public $index;
	public $path;
	public $features;
	public $currentFileInfo;
	public $css;
	public $skip;
	public $root;
	public $type = 'Import';

    public function __construct($path, $features, $options, $index, $currentFileInfo = null ){
		$this->options = $options;
		$this->index = $index;
		$this->path = $path;
		$this->features = $features;
		$this->currentFileInfo = $currentFileInfo;

		if( is_array($options) ){
			$this->options += array('inline'=>false);

			if( isset($this->options['less']) || $this->options['inline'] ){
				$this->css = !isset($this->options['less']) || !$this->options['less'] || $this->options['inline'];
			} else {
				$pathValue = $this->getPath();
				if( $pathValue && preg_match('/css([\?;].*)?$/',$pathValue) ){
					$this->css = true;
				}
			}
		}
	}

//
// The actual import node doesn't return anything, when converted to CSS.
// The reason is that it's used at the evaluation stage, so that the rules
// it imports can be treated like any other rules.
//
// In `eval`, we make sure all Import nodes get evaluated, recursively, so
// we end up with a flat structure, which can easily be imported in the parent
// ruleset.
//

    public function accept($visitor){

		if( $this->features ){
			$this->features = $visitor->visitObj($this->features);
		}
		$this->path = $visitor->visitObj($this->path);

		if( !$this->options['inline'] && $this->root ){
			$this->root = $visitor->visit($this->root);
		}
	}

    /**
     * @see Less_Tree::genCSS
     */
    public function genCSS( $output ){
		if( $this->css ){

			$output->add( '@import ', $this->currentFileInfo, $this->index );

			$this->path->genCSS( $output );
			if( $this->features ){
				$output->add( ' ' );
				$this->features->genCSS( $output );
			}
			$output->add( ';' );
		}
	}

    public function toCSS(){
		$features = $this->features ? ' ' . $this->features->toCSS() : '';

		if ($this->css) {
			return "@import " . $this->path->toCSS() . $features . ";\n";
		} else {
			return "";
		}
	}

	/**
	 * @return string
	 */
    public function getPath(){
		if ($this->path instanceof Less_Tree_Quoted) {
			$path = $this->path->value;
			$path = ( isset($this->css) || preg_match('/(\.[a-z]*$)|([\?;].*)$/',$path)) ? $path : $path . '.less';
		} else if ($this->path instanceof Less_Tree_URL) {
			$path = $this->path->value->value;
		}else{
			return null;
		}

		//remove query string and fragment
		return preg_replace('/[\?#][^\?]*$/','',$path);
	}

    public function compileForImport( $env ){
		return new Less_Tree_Import( $this->path->compile($env), $this->features, $this->options, $this->index, $this->currentFileInfo);
	}

    public function compilePath($env) {
		$path = $this->path->compile($env);
		$rootpath = '';
		if( $this->currentFileInfo && $this->currentFileInfo['rootpath'] ){
			$rootpath = $this->currentFileInfo['rootpath'];
		}


		if( !($path instanceof Less_Tree_URL) ){
			if( $rootpath ){
				$pathValue = $path->value;
				// Add the base path if the import is relative
				if( $pathValue && Less_Environment::isPathRelative($pathValue) ){
					$path->value = $this->currentFileInfo['uri_root'].$pathValue;
				}
			}
			$path->value = Less_Environment::normalizePath($path->value);
		}



		return $path;
	}

    public function compile( $env ){

		$evald = $this->compileForImport($env);

		//get path & uri
		$path_and_uri = null;
		if( is_callable(Less_Parser::$options['import_callback']) ){
			$path_and_uri = call_user_func(Less_Parser::$options['import_callback'],$evald);
		}

		if( !$path_and_uri ){
			$path_and_uri = $evald->PathAndUri();
		}

		if( $path_and_uri ){
			list($full_path, $uri) = $path_and_uri;
		}else{
			$full_path = $uri = $evald->getPath();
		}


		//import once
		if( $evald->skip( $full_path, $env) ){
			return array();
		}

		if( $this->options['inline'] ){
			//todo needs to reference css file not import
			//$contents = new Less_Tree_Anonymous($this->root, 0, array('filename'=>$this->importedFilename), true );

			Less_Parser::AddParsedFile($full_path);
			$contents = new Less_Tree_Anonymous( file_get_contents($full_path), 0, array(), true );

			if( $this->features ){
				return new Less_Tree_Media( array($contents), $this->features->value );
			}

			return array( $contents );
		}


		// css ?
		if( $evald->css ){
			$features = ( $evald->features ? $evald->features->compile($env) : null );
			return new Less_Tree_Import( $this->compilePath( $env), $features, $this->options, $this->index);
		}


		return $this->ParseImport( $full_path, $uri, $env );
	}


	/**
	 * Using the import directories, get the full absolute path and uri of the import
	 *
	 * @param Less_Tree_Import $evald
	 */
    public function PathAndUri(){

		$evald_path = $this->getPath();

		if( $evald_path ){

			$import_dirs = array();

			if( Less_Environment::isPathRelative($evald_path) ){
				//if the path is relative, the file should be in the current directory
				$import_dirs[ $this->currentFileInfo['currentDirectory'] ] = $this->currentFileInfo['uri_root'];

			}else{
				//otherwise, the file should be relative to the server root
				$import_dirs[ $this->currentFileInfo['entryPath'] ] = $this->currentFileInfo['entryUri'];

				//if the user supplied entryPath isn't the actual root
				$import_dirs[ $_SERVER['DOCUMENT_ROOT'] ] = '';

			}

			// always look in user supplied import directories
			$import_dirs = array_merge( $import_dirs, Less_Parser::$options['import_dirs'] );


			foreach( $import_dirs as $rootpath => $rooturi){
				if( is_callable($rooturi) ){
					list($path, $uri) = call_user_func($rooturi, $evald_path);
					if( is_string($path) ){
						$full_path = $path;
						return array( $full_path, $uri );
					}
				}elseif( !empty($rootpath) ){


					if( $rooturi ){
						if( strpos($evald_path,$rooturi) === 0 ){
							$evald_path = substr( $evald_path, strlen($rooturi) );
						}
					}

					$path = rtrim($rootpath,'/\\').'/'.ltrim($evald_path,'/\\');

					if( file_exists($path) ){
						$full_path = Less_Environment::normalizePath($path);
						$uri = Less_Environment::normalizePath(dirname($rooturi.$evald_path));
						return array( $full_path, $uri );
					} elseif( file_exists($path.'.less') ){
						$full_path = Less_Environment::normalizePath($path.'.less');
						$uri = Less_Environment::normalizePath(dirname($rooturi.$evald_path.'.less'));
						return array( $full_path, $uri );
					}
				}
			}
		}
	}


	/**
	 * Parse the import url and return the rules
	 *
	 * @return Less_Tree_Media|array
	 */
    public function ParseImport( $full_path, $uri, $env ){

		$import_env = clone $env;
		if( (isset($this->options['reference']) && $this->options['reference']) || isset($this->currentFileInfo['reference']) ){
			$import_env->currentFileInfo['reference'] = true;
		}

		if( (isset($this->options['multiple']) && $this->options['multiple']) ){
			$import_env->importMultiple = true;
		}

		$parser = new Less_Parser($import_env);
		$root = $parser->parseFile($full_path, $uri, true);


		$ruleset = new Less_Tree_Ruleset(array(), $root->rules );
		$ruleset->evalImports($import_env);

		return $this->features ? new Less_Tree_Media($ruleset->rules, $this->features->value) : $ruleset->rules;
	}


	/**
	 * Should the import be skipped?
	 *
	 * @return boolean|null
	 */
	private function Skip($path, $env){

		$path = Less_Parser::winPath(realpath($path));

		if( $path && Less_Parser::FileParsed($path) ){

			if( isset($this->currentFileInfo['reference']) ){
				return true;
			}

			return !isset($this->options['multiple']) && !$env->importMultiple;
		}

	}
}

