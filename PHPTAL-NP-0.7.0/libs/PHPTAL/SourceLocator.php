<?php

/**
 * Interface for template source locators.
 *
 * A locator is a way to retrieve a PHPTAL template source otherwhere than in
 * the file system.
 *
 * The default behavour of this class is to act like a file system locator.
 *
 * @author    Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_SourceLocator
{
    var $path;
    
    function PHPTAL_SourceLocator($path)
    {
        $this->path = $path;
    }
    
    /**
     * Returns an absolute path to this resource.
     * 
     * The result of this method is used to generate some unique
     * md5 value which represent the template php function name
     * and the intermediate php file name.
     *
     * @return string
     */
    function realPath()
    {
        return $this->path;
    }

    /**
     * Return source last modified date in a filemtime format.
     *
     * The result is compared to php intermediate mtime to decide
     * weither or not to re-parse the template source.
     *
     * @return int
     */
    function lastModified()
    {
        return filemtime($this->path);
    }

    /**
     * Return the template source.
     *
     * This method is invoqued if the template has to be parsed.
     *
     * @return string
     */
    function data()
    {
        return join('', file($this->path));
    }
}

?>
