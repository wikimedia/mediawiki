<?php
/**
 * This file is part of the Composer Merge plugin.
 *
 * Copyright (C) 2015 Bryan Davis, Wikimedia Foundation, and contributors
 *
 * This software may be modified and distributed under the terms of the MIT
 * license. See the LICENSE file for details.
 */

namespace Wikimedia\Composer;

use Composer\Composer;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Factory;
use Composer\Installer;
use Composer\Installer\InstallerEvent;
use Composer\Installer\InstallerEvents;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Package\AliasPackage;
use Composer\Package\BasePackage;
use Composer\Package\CompletePackage;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\RootPackage;
use Composer\Package\Version\VersionParser;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use UnexpectedValueException;

/**
 * Composer plugin that allows merging multiple composer.json files.
 *
 * When installed, this plugin will look for a "merge-patterns" key in the
 * composer configuration's "extra" section. The value of this setting can be
 * either a single value or an array of values. Each value is treated as
 * a glob() pattern identifying additional composer.json style configuration
 * files to merge into the configuration for the current compser execution.
 *
 * The "require", "require-dev", "repositories" and "suggest" sections of the
 * found configuration files will be merged into the root package
 * configuration as though they were directly included in the top-level
 * composer.json file.
 *
 * If included files specify conflicting package versions for "require" or
 * "require-dev", the normal Composer dependency solver process will be used
 * to attempt to resolve the conflict.
 *
 * @code
 * {
 *     "require": {
 *         "wikimedia/composer-merge-plugin": "dev-master"
 *     },
 *     "extra": {
 *         "merge-plugin": {
 *             "include": [
 *                 "composer.local.json"
 *             ]
 *         }
 *     }
 * }
 * @endcode
 *
 * @author Bryan Davis <bd808@bd808.com>
 */
class MergePlugin implements PluginInterface, EventSubscriberInterface
{

    /**
     * Offical package name
     */
    const PACKAGE_NAME = 'wikimedia/composer-merge-plugin';

    /**
     * @var Composer $composer
     */
    protected $composer;

    /**
     * @var IOInterface $inputOutput
     */
    protected $inputOutput;

    /**
     * @var ArrayLoader $loader
     */
    protected $loader;

    /**
     * @var array $duplicateLinks
     */
    protected $duplicateLinks;

    /**
     * @var bool $devMode
     */
    protected $devMode;

    /**
     * Whether to recursively include dependencies
     *
     * @var bool $recurse
     */
    protected $recurse = true;

    /**
     * Files that have already been processed
     *
     * @var string[] $loadedFiles
     */
    protected $loadedFiles = array();

    /**
     * Is this the first time that our plugin has been installed?
     *
     * @var bool $pluginFirstInstall
     */
    protected $pluginFirstInstall;

    /**
     * Is the autoloader file supposed to be written out?
     *
     * @var bool $dumpAutoloader
     */
    protected $dumpAutoloader;

    /**
     * Is the autoloader file supposed to be optimized?
     *
     * @var bool $optimizeAutoloader
     */
    protected $optimizeAutoloader;

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->inputOutput = $io;
        $this->pluginFirstInstall = false;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            InstallerEvents::PRE_DEPENDENCIES_SOLVING => 'onDependencySolve',
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstallOrUpdate',
            ScriptEvents::POST_UPDATE_CMD => 'onPostInstallOrUpdate',
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'onInstallUpdateOrDump',
            ScriptEvents::PRE_INSTALL_CMD => 'onInstallUpdateOrDump',
            ScriptEvents::PRE_UPDATE_CMD => 'onInstallUpdateOrDump',
        );
    }

    /**
     * Handle an event callback for an install, update or dump command by
     * checking for "merge-patterns" in the "extra" data and merging package
     * contents if found.
     *
     * @param Event $event
     */
    public function onInstallUpdateOrDump(Event $event)
    {
        $config = $this->readConfig($this->getRootPackage());
        if (isset($config['recurse'])) {
            $this->recurse = (bool)$config['recurse'];
        }
        if ($config['include']) {
            $this->loader = new ArrayLoader();
            $this->duplicateLinks = array(
                'require' => array(),
                'require-dev' => array(),
            );
            $this->devMode = $event->isDevMode();
            $this->mergePackages($config);
        }

        if ($event->getName() === ScriptEvents::PRE_AUTOLOAD_DUMP) {
            $this->dumpAutoloader = true;
            $flags = $event->getFlags();
            if (isset($flags['optimize'])) {
                $this->optimizeAutoloader = $flags['optimize'];
            }
        }
    }

    /**
     * @param RootPackage $package
     * @return array
     */
    protected function readConfig(RootPackage $package)
    {
        $config = array(
            'include' => array(),
        );
        $extra = $package->getExtra();
        if (isset($extra['merge-plugin'])) {
            $config = array_merge($config, $extra['merge-plugin']);
            if (!is_array($config['include'])) {
                $config['include'] = array($config['include']);
            }
        }
        return $config;
    }

    /**
     * Find configuration files matching the configured glob patterns and
     * merge their contents with the master package.
     *
     * @param array $config
     */
    protected function mergePackages(array $config)
    {
        $root = $this->getRootPackage();
        foreach (array_reduce(
            array_map('glob', $config['include']),
            'array_merge',
            array()
        ) as $path) {
            $this->loadFile($root, $path);
        }
    }

    /**
     * Read a JSON file and merge its contents
     *
     * @param RootPackage $root
     * @param string $path
     */
    protected function loadFile(RootPackage $root, $path)
    {
        if (in_array($path, $this->loadedFiles)) {
            $this->debug("Skipping duplicate <comment>$path</comment>...");
            return;
        } else {
            $this->loadedFiles[] = $path;
        }
        $this->debug("Loading <comment>{$path}</comment>...");
        $json = $this->readPackageJson($path);
        $package = $this->jsonToPackage($json);

        $this->mergeRequires($root, $package);
        $this->mergeDevRequires($root, $package);
        $this->mergeAutoload($root, $package, $path);

        if (isset($json['repositories'])) {
            $this->addRepositories($json['repositories'], $root);
        }

        if ($package->getSuggests()) {
            $root->setSuggests(array_merge(
                $root->getSuggests(),
                $package->getSuggests()
            ));
        }

        if ($this->recurse && isset($json['extra']['merge-plugin'])) {
            $this->mergePackages($json['extra']['merge-plugin']);
        }
    }

    /**
     * Read the contents of a composer.json style file into an array.
     *
     * The package contents are fixed up to be usable to create a Package
     * object by providing dummy "name" and "version" values if they have not
     * been provided in the file. This is consistent with the default root
     * package loading behavior of Composer.
     *
     * @param string $path
     * @return array
     */
    protected function readPackageJson($path)
    {
        $file = new JsonFile($path);
        $json = $file->read();
        if (!isset($json['name'])) {
            $json['name'] = 'merge-plugin/' .
                strtr($path, DIRECTORY_SEPARATOR, '-');
        }
        if (!isset($json['version'])) {
            $json['version'] = '1.0.0';
        }
        return $json;
    }

    /**
     * @param RootPackage $root
     * @param CompletePackage $package
     */
    protected function mergeRequires(
        RootPackage $root,
        CompletePackage $package
    ) {
        $requires = $package->getRequires();
        if (empty($requires)) {
            return;
        }

        $this->mergeStabilityFlags($root, $requires);

        $root->setRequires($this->mergeLinks(
            $root->getRequires(),
            $requires,
            $this->duplicateLinks['require']
        ));
    }

    /**
     * @param RootPackage $root
     * @param CompletePackage $package
     */
    protected function mergeDevRequires(
        RootPackage $root,
        CompletePackage $package
    ) {
        $requires = $package->getDevRequires();
        if (empty($requires)) {
            return;
        }

        $this->mergeStabilityFlags($root, $requires);

        $root->setDevRequires($this->mergeLinks(
            $root->getDevRequires(),
            $requires,
            $this->duplicateLinks['require-dev']
        ));
    }

    /**
     * @param RootPackage $root
     * @param CompletePackage $package
     * @param string $path
     */
    protected function mergeAutoload(
        RootPackage $root,
        CompletePackage $package,
        $path
    ) {
        $autoload = $package->getAutoload();
        if (empty($autoload)) {
            return;
        }

        $packagePath = substr($path, 0, strrpos($path, '/') + 1);

        array_walk_recursive(
            $autoload,
            function(&$path) use ($packagePath) {
                $path = $packagePath . $path;
            }
        );

        $root->setAutoload(array_merge_recursive(
            $root->getAutoload(),
            $autoload
        ));
    }

    /**
     * Extract and merge stability flags from the given collection of
     * requires.
     *
     * @param RootPackage $root
     * @param array $requires
     */
    protected function mergeStabilityFlags(
        RootPackage $root,
        array $requires
    ) {
        $flags = $root->getStabilityFlags();
        foreach ($requires as $name => $link) {
            $name = strtolower($name);
            $version = $link->getPrettyConstraint();
            $stability = VersionParser::parseStability($version);
            $flags[$name] = BasePackage::$stabilities[$stability];
        }
        $root->setStabilityFlags($flags);
    }

    /**
     * Add a collection of repositories described by the given configuration
     * to the given package and the global repository manager.
     *
     * @param array $repositories
     * @param RootPackage $root
     */
    protected function addRepositories(
        array $repositories,
        RootPackage $root
    ) {
        $repoManager = $this->composer->getRepositoryManager();
        $newRepos = array();

        foreach ($repositories as $repoJson) {
            if (!isset($repoJson['type'])) {
                continue;
            }
            $this->debug("Adding {$repoJson['type']} repository");
            $repo = $repoManager->createRepository(
                $repoJson['type'],
                $repoJson
            );
            $repoManager->addRepository($repo);
            $newRepos[] = $repo;
        }

        $root->setRepositories(array_merge(
            $newRepos,
            $root->getRepositories()
        ));
    }

    /**
     * Merge two collections of package links and collect duplicates for
     * subsequent processing.
     *
     * @param array $origin Primary collection
     * @param array $merge Additional collection
     * @param array &dups Duplicate storage
     * @return array Merged collection
     */
    protected function mergeLinks(array $origin, array $merge, array &$dups)
    {
        foreach ($merge as $name => $link) {
            if (!isset($origin[$name])) {
                $this->debug("Merging <comment>{$name}</comment>");
                $origin[$name] = $link;
            } else {
                // Defer to solver.
                $this->debug("Deferring duplicate <comment>{$name}</comment>");
                $dups[] = $link;
            }
        }
        return $origin;
    }

    /**
     * Handle an event callback for pre-dependency solving phase of an install
     * or update by adding any duplicate package dependencies found during
     * initial merge processing to the request that will be processed by the
     * dependency solver.
     *
     * @param InstallerEvent $event
     */
    public function onDependencySolve(InstallerEvent $event)
    {
        if (empty($this->duplicateLinks)) {
            // @codeCoverageIgnoreStart
            // We shouldn't really ever be able to get here as this event is
            // triggered inside Composer\Installer and should have been
            // preceded by a pre-install or pre-update event but better to
            // have an unneeded check than to break with some future change in
            // the event system.
            return;
            // @codeCoverageIgnoreEnd
        }

        $request = $event->getRequest();
        foreach ($this->duplicateLinks['require'] as $link) {
            $this->debug("Adding dependency <comment>{$link}</comment>");
            $request->install($link->getTarget(), $link->getConstraint());
        }
        if ($this->devMode) {
            foreach ($this->duplicateLinks['require-dev'] as $link) {
                $this->debug("Adding dev dependency <comment>{$link}</comment>");
                $request->install($link->getTarget(), $link->getConstraint());
            }
        }
    }

    /**
     * Handle an event callback following installation of a new package by
     * checking to see if the package that was installed was our plugin.
     *
     * @param PackageEvent $event
     */
    public function onPostPackageInstall(PackageEvent $event)
    {
        $op = $event->getOperation();
        if ($op instanceof InstallOperation) {
            $package = $op->getPackage()->getName();
            if ($package === self::PACKAGE_NAME) {
                $this->debug('composer-merge-plugin installed');
                $this->pluginFirstInstall = true;
            }
        }
    }

    /**
     * Is this the first time that the plugin has been installed?
     *
     * @return bool
     */
    public function isFirstInstall()
    {
        return $this->pluginFirstInstall;
    }

    /**
     * Handle an event callback following an install or update command. If our
     * plugin was installed during the run then trigger an update command to
     * process any merge-patterns in the current config.
     *
     * @param Event $event
     */
    public function onPostInstallOrUpdate(Event $event)
    {
        if ($this->pluginFirstInstall) {
            $this->pluginFirstInstall = false;
            $this->debug(
                '<comment>' .
                'Running additional update to apply merge settings' .
                '</comment>'
            );

            $config = $this->composer->getConfig();

            $preferSource = $config->get('preferred-install') == 'source';
            $preferDist = $config->get('preferred-install') == 'dist';

            $installer = Installer::create(
                $event->getIO(),
                // Create a new Composer instance to ensure full processing of
                // the merged files.
                Factory::create($event->getIO(), null, false)
            );

            $installer->setPreferSource($preferSource);
            $installer->setPreferDist($preferDist);
            $installer->setDevMode($event->isDevMode());
            $installer->setDumpAutoloader($this->dumpAutoloader);
            $installer->setOptimizeAutoloader($this->optimizeAutoloader);

            // Force update mode so that new packages are processed rather
            // than just telling the user that composer.json and composer.lock
            // don't match.
            $installer->setUpdate(true);

            $installer->run();
        }
    }

    /**
     * @return RootPackage
     */
    protected function getRootPackage()
    {
        $root = $this->composer->getPackage();
        if ($root instanceof AliasPackage) {
            $root = $root->getAliasOf();
        }
        // @codeCoverageIgnoreStart
        if (!$root instanceof RootPackage) {
            throw new UnexpectedValueException(
                'Expected instance of RootPackage, got ' . get_class($root)
            );
        }
        // @codeCoverageIgnoreEnd
        return $root;
    }

    /**
     * @return CompletePackage
     */
    protected function jsonToPackage($json)
    {
        $package = $this->loader->load($json);
        // @codeCoverageIgnoreStart
        if (!$package instanceof CompletePackage) {
            throw new UnexpectedValueException(
                'Expected instance of CompletePackage, got ' .
                get_class($package)
            );
        }
        // @codeCoverageIgnoreEnd
        return $package;
    }

    /**
     * Log a debug message
     *
     * Messages will be output at the "verbose" logging level (eg `-v` needed
     * on the Composer command).
     *
     * @param string $message
     */
    protected function debug($message)
    {
        // @codeCoverageIgnoreStart
        if ($this->inputOutput->isVerbose()) {
            $message = "  <info>[merge]</info> {$message}";

            if (method_exists($this->inputOutput, 'writeError')) {
                $this->inputOutput->writeError($message);
            } else {
                // Backwards compatiblity for Composer before cb336a5
                $this->inputOutput->write($message);
            }
        }
        // @codeCoverageIgnoreEnd
    }
}
// vim:sw=4:ts=4:sts=4:et:
