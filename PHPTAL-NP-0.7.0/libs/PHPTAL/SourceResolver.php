<?php

/**
 * This class is used to resolve template source path outside the file system.
 *
 * Given a path, a template repository and a template caller path, one resolver
 * must decide whether or not it can retrieve the template source.
 *
 * @author    Laurent Bedubourg <laurent.bedubourg@free.fr>
 */
class PHPTAL_SourceResolver
{
    /**
     * Resolve a template source path.
     *
     * This method is invoked each time a template source has to be
     * located.
     *
     * This method must returns a PHPTAL_SourceLocator object which
     * 'point' to the template source and is able to retrieve it.
     *
     * If the resolver does not handle this kind of path, it must return
     * 'false' so PHPTAL will ask other resolvers.
     *
     * @param string $path       -- path to resolve
     *
     * @param string $repository -- templates repository if specified on
     *                              on template creation. 
     *
     * @param string $callerPath -- caller realpath when a template look
     *                              for an external template or macro,
     *                              this should be usefull for relative urls
     *
     * @return PHPTAL_SourceLocator | false 
     */
    function resolve($path, $repository=false, $callerPath=false)
    {
        // first look at an absolute path
        $pathes = array($path);
        // search in the caller directory
        if ($callerPath) {
            $pathes[] = dirname($callerPath) . PHPTAL_PATH_SEP . $path;
        }
        // search in the template repository
        if ($repository) {
            $pathes[] = $repository . PHPTAL_PATH_SEP . $path;
        }
        // search in the defined repository
        if (defined('PHPTAL_REPOSITORY')) {
            $pathes[] = PHPTAL_REPOSITORY . PHPTAL_PATH_SEP . $path;
        }
        foreach ($pathes as $ftest) {
            if (file_exists($ftest)) {
                $realpath = realpath($ftest);
                $locator  = new PHPTAL_SourceLocator($realpath);
                return $locator;
            }
        }
        return false;
    }
}

?>
