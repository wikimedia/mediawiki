<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
//  
//  Copyright (c) 2003 Laurent Bedubourg
//  
//  This library is free software; you can redistribute it and/or
//  modify it under the terms of the GNU Lesser General Public
//  License as published by the Free Software Foundation; either
//  version 2.1 of the License, or (at your option) any later version.
//  
//  This library is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
//  Lesser General Public License for more details.
//  
//  You should have received a copy of the GNU Lesser General Public
//  License along with this library; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
//  
//  Authors: Laurent Bedubourg <laurent.bedubourg@free.fr>
//  


class PHPTAL_Template
{
    var $_ctx;
    var $_code;
    var $_codeFile;
    var $_funcName;
    var $_sourceFile;
    var $_error         = false;
    var $_repository    = false;
    var $_cacheDir     = false;
    var $_parent        = false;
    var $_parentPath   = false;
    var $_prepared      = false;
    var $_cacheManager;

    var $_outputMode    = PHPTAL_XHTML;

    var $_inputFilters;
    var $_outputFilters;
    var $_resolvers;
    var $_locator;

    var $_headers = false;

    var $_translator;

    var $_encoding = 'UTF-8';
    
    /**
     * Template object constructor.
     *
     * @param string $file -- The source file name
     * @param string $repository optional -- Your templates root.
     * @param string $cache_dir optional -- Intermediate php code repository.
     */
    function PHPTAL_Template($file, $repository=false, $cache_dir=false)
    {
        $this->_sourceFile   = $file;
        $this->_repository = $repository;

        // deduce intermediate php code cache directory
        if (!$cache_dir) {
            if (defined('PHPTAL_CACHE_DIR')) {
                $cache_dir = PHPTAL_CACHE_DIR; 
            } else {
                $cache_dir = PHPTAL_DEFAULT_CACHE_DIR;
            }
        }
        $this->_cacheDir  = $cache_dir;

        // instantiate a new context for this template
        // !!! this context may be overwritten by a parent context
        $this->_ctx        = new PHPTAL_Context();
        
        // create resolver vector and the default filesystem resolver
        $this->_resolvers  = new OArray();
        $this->_resolvers->push(new PHPTAL_SourceResolver());
        
        // vector for source filters
        $this->_inputFilters = new OArray();
        $this->_outputFilters = new OArray();
        
        // if no cache manager set, we instantiate default dummy one
        if (!isset($this->_cacheManager)) {
            $this->_cacheManager = new PHPTAL_Cache();
        }
    }

    /**
     * Set template ouput type.
     *
     * Default output is XHTML, so you'll have to call this method only for
     * specific xml documents  with PHPTAL_XML parameter.
     * 
     * @param int $mode -- output mode (PHPTAL_XML) as default system use XHTML
     */
    function setOutputMode($mode)
    {
        $this->_outputMode = $mode;
    }
    
    /**
     * Replace template context with specified hashtable.
     *
     * @param hash hashtable -- Associative array.
     */
    function setAll($hash)
    {
        $this->_ctx = new PHPTAL_Context($hash);
    }
    
    /**
     * Set a template context value.
     * 
     * @param string $key -- The context key
     * @param mixed  $value -- The context value
     */
    function set($name, $value)
    {
        $this->_ctx->set($name, $value);
    }

    /**
     * Set a template context value by reference.
     * 
     * @param string $name -- The template context key
     * @param mixed $value -- The template context value
     */
    function setRef($name, &$value)
    {
        $this->_ctx->setRef($name, $value);
    }

    /**
     * Retrieve template context object.
     *
     * @return PHPTAL_Context
     */
    function &getContext()
    {
        return $this->_ctx;
    }

    /**
     * Set the template context object.
     *
     * @param PHPTAL_Context $ctx -- The context object
     */
    function setContext(&$ctx)
    {
        $this->_ctx =& $ctx;
    }

    /**
     * Set the cache manager to use for Template an Macro calls.
     *
     * @param PHPTAL_Cache $mngr -- Cache object that will be used to cache
     *                              template and macros results.
     */
    function setCacheManager(&$mngr)
    {
        $this->_cacheManager =& $mngr;
    }

    /**
     * Retrieve the cache manager used in this template.
     *
     * @return PHPTAL_Cache
     */
    function &getCacheManager()
    {
        return $this->_cacheManager;
    }

    /**
     * Set the I18N implementation to use in this template.
     *
     * @param PHPTAL_I18N $tr -- I18N implementation
     */
    function setTranslator(&$tr)
    {
        $this->_translator =& $tr;
    }
    
    /**
     * The translator used by this template.
     *
     * @return PHPTAL_I18N
     */
    function &getTranslator()
    {
        return $this->_translator;
    }
    
    /**
     * Test if the template file exists.
     * @deprecated use isValid() instead
     * @return boolean
     */
    function fileExists()
    {
        return $this->isValid();
    }

    /**
     * Test if the template resource exists.
     * 
     * @return boolean
     */    
    function isValid()
    {
        if (isset($this->_locator)) {
            return true;
        }
        
        // use template resolvers to locate template source data
        // in most cases, there will be only one resolver in the
        // resolvers list (the default one) which look on the file 
        // system.

        $i = $this->_resolvers->getNewIterator();
        while ($i->isValid()) {
            $resolver =& $i->value();
            $locator  =& $resolver->resolve($this->_sourceFile, 
                                            $this->_repository, 
                                            $this->_parentPath);
            if ($locator && !PEAR::isError($locator)) {
                $this->_locator =& $locator;
                $this->_real_path = $this->_locator->realPath();
                return true;
            }
            $i->next();
        }
        return false;
    }

    /**
     * Add a source resolver to the template.
     *
     * @param PHPTAL_SourceResolver $resolver
     *        The source resolver.
     */
    function addSourceResolver(&$resolver)
    {
        $this->_resolvers->pushRef($resolver);
    }

    /**
     * Add filter to this template input filters list.
     *
     * @param PHPTAL_Filter $filter 
     *        A filter which will be invoked on template source.
     */
    function addInputFilter(&$filter)
    {
        $this->_inputFilters->pushRef($filter);
    }

    /**
     * Add an output filter to this template output filters list.
     *
     * @param PHPTAL_Filter $filter
     *        A filter which will be invoked on template output.
     */
    function addOutputFilter(&$filter)
    {
        $this->_outputFilters->pushRef($filter);
    }
    
    /**
     * Retrieve the source template real path.
     *
     * This method store its result internally if no $file attribute is
     * specified (work on template internals).
     *
     * If a file name is specified, this method will try to locate it
     * exploring current path (PWD), the current template location, 
     * the repository and parent template location.
     * 
     * @param string $file optional 
     *        some file name to locate.
     *        
     * @throws FileNotFound 
     * @return string
     */
    function realpath($file=false)
    {
        // real template path
        if (!$file) {
            if ($this->isValid()) {
                return $this->_real_path;
            } else {
                $ex = new FileNotFound($this->_sourceFile . ' not found');
                return PEAR::raiseError($ex);
            }
        }
        
        // 
        // path to some file relative to this template
        // 
        $i = $this->_resolvers->getNewIterator();
        while ($i->isValid()) {
            $resolver =& $i->value();
            $locator  =& $resolver->resolve($file, 
                                            $this->_repository, 
                                            $this->_real_path);
            if ($locator) {
                return $locator->realPath();
            }
            $i->next();
        }

        $ex = new FileNotFound($file . ' not found');
        return PEAR::raiseError($ex);
    }

    /**
     * Set the template result encoding.
     *
     * Changing this encoding will change htmlentities behaviour.
     *
     * Example:
     *
     *          $tpl->setEncoding('ISO-8859-1");
     *
     * See http://fr2.php.net/manual/en/function.htmlentities.php for a list of
     * supported encodings.
     * 
     * @param $enc string Template encoding
     */
    function setEncoding($enc)
    {
        $this->_encoding = $enc;
    }

    /**
     * Retrieve the template result encoding.
     *
     * @return string
     */
    function getEncoding()
    {
        return $this->_encoding;
    }

    // ----------------------------------------------------------------------
    // private / protected methods
    // ----------------------------------------------------------------------
    
    /**
     * Set the called template. (internal)
     *
     * @access package
     */
    function setParent(&$tpl)
    {
        $this->_parent =& $tpl;
        $this->_resolvers = $tpl->_resolvers;
        $this->_inputFilters = $tpl->_inputFilters;
        $this->_parentPath = $tpl->realPath();
        $this->_cacheManager =& $tpl->getCacheManager();
        $this->_translator =& $tpl->_translator;
        $this->setOutputMode($tpl->_outputMode);
    }

    /**
     * Prepare template execution.
     *
     * @access private
     */
    function _prepare()
    {
        if ($this->_prepared) return;
        $this->_sourceFile = $this->realpath();
        
        // ensure that no error remain
        if (PEAR::isError($this->_sourceFile)) {
            return $this->_sourceFile; 
        }
        $this->_funcName   = "tpl_" . PHPTAL_MARK . md5($this->_sourceFile);
        $this->_codeFile   = $this->_cacheDir . $this->_funcName . '.php';
        $this->_prepared    = true;
    }
    
    /**
     * Generate php code from template source
     * 
     * @access private
     * @throws PHPTALParseException
     */
    function _generateCode()
    {
        require_once _phptal_os_path_join(dirname(__FILE__), 'Parser.php');

        $parser = new PHPTAL_Parser();
        $parser->_outputMode($this->_outputMode);
        $data   = $this->_locator->data();

        // activate prefilters on data
        $i = $this->_inputFilters->getNewIterator();
        while ($i->isValid()){
            $filter =& $i->value();
            $data = $filter->filter($data);
            $i->next();
        }

        // parse source
        $result = $parser->parse($this->_real_path, $data);
        if (PEAR::isError($result)) {
            return $result;
        }

        // generate and store intermediate php code
        $this->_code = $parser->generateCode($this->_funcName);
        if (PEAR::isError($this->_code)) {
            return $this->_code;
        }
    }

    /**
     * Load cached php code
     * 
     * @access private
     */
    function _loadCachedCode()
    {
        include_once($this->_codeFile);
        $this->_code = "#loaded";    
    }

    /**
     * Cache generated php code.
     * 
     * @access private
     */
    function _cacheCode()
    {
        $fp = @fopen($this->_codeFile, "w");
        if (!$fp) {
            return PEAR::raiseError($php_errormsg);
        }
        fwrite($fp, $this->_code);
        fclose($fp);
    }
    
    /**
     * Load or generate php code.
     * 
     * @access private
     */
    function _load()
    {
        if (isset($this->_code) && !PEAR::isError($this->_code)) { 
            return; 
        }
        
        if (!defined('PHPTAL_NO_CACHE') 
            && file_exists($this->_codeFile) 
            && filemtime($this->_codeFile) >= $this->_locator->lastModified()) {
            return $this->_loadCachedCode();
        }
        
        $err = $this->_generateCode();
        if (PEAR::isError($err)) {
            return $err;
        }
        
        $err = $this->_cacheCode();
        if (PEAR::isError($err)) {
            return $err;
        }

        $err = $this->_loadCachedCode();
        if (PEAR::isError($err)) { 
            return $err;
        }
    }
    
    /**
     * Execute template with prepared context.
     *
     * This method execute the template file and returns the produced string.
     * 
     * @return string
     * @throws 
     */
    function execute()
    {
        $err = $this->_prepare();
        if (PEAR::isError($err)) {
            $this->_ctx->_errorRaised = true;
            return $err;
        }
        return $this->_cacheManager->template($this, 
                                               $this->_sourceFile, 
                                               $this->_ctx);
    }

    /**
     * Really load/parse/execute the template and process output filters.
     *
     * This method is called by cache manager to retrieve the real template
     * execution value.
     *
     * IMPORTANT : The result is post-filtered here !
     * 
     * @return string
     * @access private
     */
    function _process()
    {
        $err = $this->_load();
        if (PEAR::isError($err)) { 
            $this->_ctx->_errorRaised = true;
            return $err;
        }

        $this->_ctx->_errorRaised = false;
        $func = $this->_funcName;
        if (!function_exists($func)) {
            $err = "Template function '$func' not found (template source : $this->_sourceFile";
            return PEAR::raiseError($err);
        }
        
        // ensure translator exists
        if (!isset($this->_translator)) {
            $this->_translator = new PHPTAL_I18N();
        }
        
        $res = $func($this);
        if ($this->_headers) {
            $res = $this->_headers . $res;
        }
        
        // activate post filters
        $i = $this->_outputFilters->getNewIterator();
        while ($i->isValid()) {
            $filter =& $i->value();
            $res = $filter->filter($this, $res, PHPTAL_POST_FILTER);
            $i->next();
        }
        return $res;
    }

    function _translate($key)
    {
        return $this->_translator->translate($key);
    }

    function _setTranslateVar($name, $value)
    {
        if (is_object($value)) {
            $value = $value->toString();
        }
        $this->_translator->set($name, $value);
    }
}

?>
