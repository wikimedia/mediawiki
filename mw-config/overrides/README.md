# bluespice/config-mw/overrides (Installer for BlueSpice 3 on top of MediaWiki 1.31)

This repo contains all the implementation _and_ resources/assets that are required for the installer UI

Installation steps for Linux:
```
 # get fork of mediawiki in REL1_31 with depth = 1
 git clone https://github.com/hallowelt/mediawiki bluespice3 --depth 1 --branch REL1_31
 cd bluespice3
 # init submodules - this populates .git/modules
 git submodule update --init
 # submodules might be in detached state
 git submodule foreach -q --recursive 'git checkout REL1_31'
 # run composer
 composer update
 # copy directories
 cp extensions/BlueSpiceFoundation/data.template/ extensions/BlueSpiceFoundation/data -r
 cp extensions/BlueSpiceFoundation/config.template/ extensions/BlueSpiceFoundation/config -r
 # set rights the easy way - don't do that in production!
 # chmod a+w * --recursive
 # set only right for those directories which need to be writable
 # TODO: depends on server; is that all?
 chmod og-w * --recursive
 chmod a+w cache --recursive
 chmod a+w images --recursive
 chmod a+w extensions/BlueSpiceFoundation/data --recursive
 chmod a+w extensions/BlueSpiceFoundation/config --recursive

 echo "ready for install with WebInstaller"
```

Install with cli:
```
 php maintenance/install.php --dbname DBNAME --dbpass DBPASS --dbserver DBSERVER --dbuser DBUSER --pass hallowelt --server http://localhost --wiki bluespice3 --scriptpath /bluespice3 --with-extensions bluespice3 WikiSysop
```
