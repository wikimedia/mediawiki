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

require_once "PEAR.php";

define('GETTEXT_NATIVE', 1);
define('GETTEXT_PHP', 2);

/**
 * Generic gettext static class.
 *
 * This class allows gettext usage with php even if the gettext support is 
 * not compiled in php.
 *
 * The developper can choose between the GETTEXT_NATIVE support and the
 * GETTEXT_PHP support on initialisation. If native is not supported, the
 * system will fall back to PHP support.
 *
 * On both systems, this package add a variable interpolation system so you can
 * translate entire dynamic sentences in stead of peace of sentences.
 *
 * Small example without pear error lookup :
 * 
 * <?php
 * require_once "GetText.php";
 *
 * GetText::init();
 * GetText::setLanguage('fr_Fr');      // may throw GetText_Error
 * GetText::addDomain('myAppDomain');  // may throw GetText_Error
 * GetText::setVar('login', $login);   
 * GetText::setVar('name', $name);
 * 
 * // may throw GetText_Error
 * echo GetText::gettext('Welcome ${name}, you\'re connected with login ${login}');
 * 
 * // should echo something like :
 * //
 * // "Bienvenue Jean-Claude, vous êtes connecté en tant qu'utilisateur jcaccount"
 * // 
 * // or if fr_FR translation does not exists
 * //
 * // "Welcome Jean-Claude, you're connected with login jcaccount"
 * 
 * ?>
 *
 * A gettext mini-howto should be provided with this package, if you're new 
 * to gettext usage, please read it to learn how to build a gettext 
 * translation directory (locale).
 * 
 * @todo    Tools to manage gettext files in php.
 * 
 *          - non traducted domains / keys
 *          - modification of keys
 *          - domain creation, preparation, delete, ...
 *          - tool to extract required messages from TOF templates
 *
 * @version 0.5
 * @author  Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class GetText
{
    /**
     * This method returns current gettext support class.
     *
     * @return GetText_Support
     * @static 1
     * @access private
     */
    function &_support($set=false)
    { 
        static $supportObject;
        if ($set !== false) { 
            $supportObject = $set; 
        } elseif (!isset($supportObject)) {
            trigger_error("GetText not initialized !". endl.
                          "Please call GetText::init() before calling ".
                          "any GetText function !".endl
                          , E_USER_ERROR);
        }
        return $supportObject;
    }
    
    /**
     * Initialize gettext package.
     *
     * This method instantiate the gettext support depending on managerType
     * value. 
     *
     * GETTEXT_NATIVE try to use gettext php support and fail back to PHP
     * support if not installed.
     *
     * GETTEXT_PHP explicitely request the usage of PHP support.
     *
     * @param  int $managerType
     *         Gettext support type.
     *         
     * @access public
     * @static 1
     */
    function init($managerType = GETTEXT_NATIVE)
    {
        if ($managerType == GETTEXT_NATIVE) {
            if (function_exists('gettext')) {
                return GetText::_support(new GetText_NativeSupport());
            }
        }
        // fail back to php support 
        return GetText::_support(new GetText_PHPSupport());
    }
    
    /**
     * Set the language to use for traduction.
     *
     * @param string $langCode
     *        The language code usually defined as ll_CC, ll is the two letter
     *        language code and CC is the two letter country code.
     *
     * @throws GetText_Error if language is not supported by your system.
     */
    function setLanguage($langCode)
    {
        $support =& GetText::_support();
        return $support->setLanguage($langCode);
    }
    
    /**
     * Add a translation domain.
     *
     * The domain name is usually the name of the .po file you wish to use. 
     * For example, if you created a file 'locale/ll_CC/LC_MESSAGES/myapp.po',
     * you'll use 'myapp' as the domain name.
     *
     * @param string $domain
     *        The domain name.
     *
     * @param string $path optional
     *        The path to the locale directory (ie: /path/to/locale/) which
     *        contains ll_CC directories.
     */
    function addDomain($domain, $path=false)
    {
        $support =& GetText::_support();
        return $support->addDomain($domain, $path);
    }
    
    /**
     * Retrieve the translation for specified key.
     *
     * @param string $key
     *        String to translate using gettext support.
     */
    function gettext($key)
    { 
        $support =& GetText::_support();
        return $support->gettext($key);
    }
   
    /**
     * Add a variable to gettext interpolation system.
     *
     * @param string $key
     *        The variable name.
     *
     * @param string $value
     *        The variable value.
     */
    function setVar($key, $value)
    {
        $support =& GetText::_support();
        return $support->setVar($key, $value);
    }

    /**
     * Add an hashtable of variables.
     *
     * @param hashtable $hash 
     *        PHP associative array of variables.
     */
    function setVars($hash)
    {
        $support =& GetText::_support();
        return $support->setVars($hash);
    }

    /**
     * Reset interpolation variables.
     */
    function reset()
    {
        $support =& GetText::_support();
        return $support->reset();
    }
}


/**
 * Interface to gettext native support.
 *
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 * @access private
 */
class GetText_NativeSupport 
{
    var $_interpolationVars = array();

    /**
     * Set gettext language code.
     * @throws GetText_Error
     */
    function setLanguage($langCode)
    {
        putenv("LANG=$langCode");
        putenv("LC_ALL=$langCode");
        putenv("LANGUAGE=$langCode");
        $set = setlocale(LC_ALL, "$langCode");
        if ($set === false) {
            $str = sprintf('Language code "%s" not supported by your system',
                           $langCode);
            $err = new GetText_Error($str);
            return PEAR::raiseError($err);
        }
    }
    
    /**
     * Add a translation domain.
     */
    function addDomain($domain, $path=false)
    {
        if ($path === false) {
            bindtextdomain($domain, "./locale/");
        } else { 
            bindtextdomain($domain, $path);
        }
        textdomain($domain);
    }
    
    /**
     * Retrieve translation for specified key.
     *
     * @access private
     */
    function _getTranslation($key)
    {
        return gettext($key);
    }
    

    /**
     * Reset interpolation variables.
     */
    function reset()
    {
        $this->_interpolationVars = array();
    }
    
    /**
     * Set an interpolation variable.
     */
    function setVar($key, $value)
    {
        $this->_interpolationVars[$key] = $value;
    }

    /**
     * Set an associative array of interpolation variables.
     */
    function setVars($hash)
    {
        $this->_interpolationVars = array_merge($this->_interpolationVars,
                                                $hash);
    }
    
    /**
     * Retrieve translation for specified key.
     *
     * @param  string $key  -- gettext msgid
     * @throws GetText_Error
     */
    function gettext($key)
    {
        $value = $this->_getTranslation($key);
        if ($value === false) {
            $str = sprintf('Unable to locate gettext key "%s"', $key);
            $err = new GetText_Error($str);
            return PEAR::raiseError($err);
        }
        
        while (preg_match('/\$\{(.*?)\}/sm', $value, $m)) {
            list($src, $var) = $m;

            // retrieve variable to interpolate in context, throw an exception
            // if not found.
            $varValue = $this->_getVar($var);
            if ($varValue === false) {
                $str = sprintf('Interpolation error, var "%s" not set', $var);
                $err = new GetText_Error($str);
                return PEAR::raiseError($err);
            }
            $value = str_replace($src, $varValue, $value);
        }
        return $value;
    }

    /**
     * Retrieve an interpolation variable value.
     * 
     * @return mixed
     * @access private
     */
    function _getVar($name)
    {
        if (!array_key_exists($name, $this->_interpolationVars)) {
            return false;
        }
        return $this->_interpolationVars[$name];
    }
}


/**
 * Implementation of GetText support for PHP.
 *
 * This implementation is abble to cache .po files into php files returning the
 * domain translation hashtable.
 *
 * @access private
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class GetText_PHPSupport extends GetText_NativeSupport
{
    var $_path     = 'locale/';
    var $_langCode = false;
    var $_domains  = array();
    var $_end      = -1;
    var $_jobs     = array();

    /**
     * Set the translation domain.
     *
     * @param  string $langCode -- language code
     * @throws GetText_Error
     */
    function setLanguage($langCode)
    {
        // if language already set, try to reload domains
        if ($this->_langCode !== false and $this->_langCode != $langCode) {
            foreach ($this->_domains as $domain) {
                $this->_jobs[] = array($domain->name, $domain->path);
            }
            $this->_domains = array();
            $this->_end = -1;
        }
        
        $this->_langCode = $langCode;

        // this allow us to set the language code after 
        // domain list.
        while (count($this->_jobs) > 0) {
            list($domain, $path) = array_shift($this->_jobs);
            $err = $this->addDomain($domain, $path);
            // error raised, break jobs
            if (PEAR::isError($err)) {
                return $err;
            }
        }
    }
    
    /**
     * Add a translation domain.
     *
     * @param string $domain        -- Domain name
     * @param string $path optional -- Repository path
     * @throws GetText_Error
     */
    function addDomain($domain, $path = "./locale/")
    {   
        if (array_key_exists($domain, $this->_domains)) { 
            return; 
        }
        
        if (!$this->_langCode) { 
            $this->_jobs[] = array($domain, $path); 
            return;
        }

        $err = $this->_loadDomain($domain, $path);
        if (PEAR::isError($err)) {
            return $err;
        }

        $this->_end++;
    }

    /**
     * Load a translation domain file.
     *
     * This method cache the translation hash into a php file unless
     * GETTEXT_NO_CACHE is defined.
     * 
     * @param  string $domain        -- Domain name
     * @param  string $path optional -- Repository
     * @throws GetText_Error
     * @access private
     */
    function _loadDomain($domain, $path = "./locale")
    {
        $srcDomain = $path . "/$this->_langCode/LC_MESSAGES/$domain.po";
        $phpDomain = $path . "/$this->_langCode/LC_MESSAGES/$domain.php";
        
        if (!file_exists($srcDomain)) {
            $str = sprintf('Domain file "%s" not found.', $srcDomain);
            $err = new GetText_Error($str);
            return PEAR::raiseError($err);
        }
        
        $d = new GetText_Domain();
        $d->name = $domain;
        $d->path = $path;
        
        if (!file_exists($phpDomain) 
            || (filemtime($phpDomain) < filemtime($srcDomain))) {
            
            // parse and compile translation table
            $parser = new GetText_PHPSupport_Parser();
            $hash   = $parser->parse($srcDomain);
            if (!defined('GETTEXT_NO_CACHE')) {
                $comp = new GetText_PHPSupport_Compiler();
                $err  = $comp->compile($hash, $srcDomain);
                if (PEAR::isError($err)) { 
                    return $err; 
                }
            }
            $d->_keys = $hash;
        } else {
            $d->_keys = include $phpDomain;
        }
        $this->_domains[] =& $d;
    }
    
    /**
     * Implementation of gettext message retrieval.
     */
    function _getTranslation($key)
    {
        for ($i = $this->_end; $i >= 0; $i--) {
            if ($this->_domains[$i]->hasKey($key)) {
                return $this->_domains[$i]->get($key);
            }
        }
        return $key;
    }
}

/**
 * Class representing a domain file for a specified language.
 *
 * @access private
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class GetText_Domain
{
    var $name;
    var $path;

    var $_keys = array();

    function hasKey($key)
    {
        return array_key_exists($key, $this->_keys);
    }

    function get($key)
    {
        return $this->_keys[$key];
    }
}

/**
 * This class is used to parse gettext '.po' files into php associative arrays.
 *
 * @access private
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class GetText_PHPSupport_Parser 
{
    var $_hash = array();
    var $_currentKey;
    var $_currentValue;
    
    /**
     * Parse specified .po file.
     *
     * @return hashtable
     * @throws GetText_Error
     */
    function parse($file)
    {
        $this->_hash = array();
        $this->_currentKey = false;
        $this->_currentValue = "";
        
        if (!file_exists($file)) {
            $str = sprintf('Unable to locate file "%s"', $file);
            $err = new GetText_Error($str);
            return PEAR::raiseError($err);
        }
        $i=0;
        $lines = file($file);
        foreach ($lines as $line) {
            $this->_parseLine($line, ++$i);
        }
        $this->_storeKey();

        return $this->_hash;
    }

    /**
     * Parse one po line.
     *
     * @access private
     */
    function _parseLine($line, $nbr)
    {
        if (preg_match('/^\s*?#/', $line)) { return; }
        if (preg_match('/^\s*?msgid \"(.*?)(?!<\\\)\"/', $line, $m)) {
            $this->_storeKey();
            $this->_currentKey = $m[1];
            return;
        }
        if (preg_match('/^\s*?msgstr \"(.*?)(?!<\\\)\"/', $line, $m)) {
            $this->_currentValue .= $m[1];
            return;
        }
        if (preg_match('/^\s*?\"(.*?)(?!<\\\)\"/', $line, $m)) {
            $this->_currentValue .= $m[1];
            return;
        }
    }

    /**
     * Store last key/value pair into building hashtable.
     *
     * @access private
     */
    function _storeKey()
    {
        if ($this->_currentKey === false) return;
        $this->_currentValue = str_replace('\\n', "\n", $this->_currentValue);
        $this->_hash[$this->_currentKey] = $this->_currentValue;
        $this->_currentKey = false;
        $this->_currentValue = "";
    }
}


/**
 * This class write a php file from a gettext hashtable.
 *
 * The produced file return the translation hashtable on include.
 * 
 * @throws GetText_Error
 * @access private
 * @author Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class GetText_PHPSupport_Compiler 
{
    /**
     * Write hash in an includable php file.
     */
    function compile(&$hash, $sourcePath)
    {
        $destPath = preg_replace('/\.po$/', '.php', $sourcePath);
        $fp = @fopen($destPath, "w");
        if (!$fp) {
            $str = sprintf('Unable to open "%s" in write mode.', $destPath);
            $err = new GetText_Error($str);
            return PEAR::raiseError($err);
        }
        fwrite($fp, '<?php' . "\n");
        fwrite($fp, 'return array(' . "\n");
        foreach ($hash as $key => $value) {
            $key   = str_replace("'", "\\'", $key);
            $value = str_replace("'", "\\'", $value);
            fwrite($fp, '    \'' . $key . '\' => \'' . $value . "',\n");
        }
        fwrite($fp, ');' . "\n");
        fwrite($fp, '?>');
        fclose($fp);
    }
}

/**
 * GetText related error.
 */
class GetText_Error extends PEAR_Error {}

?>
