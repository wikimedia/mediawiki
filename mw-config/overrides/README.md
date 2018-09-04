# bluespice/config-mw/overrides (Installer for BlueSpice 3 on top of MediaWiki 1.31)

This repo contains all the implementation _and_ resources/assets that are required for the installer UI
```
 # get fork of mediawiki
 git clone https://github.com/hallowelt/mediawiki bluespice3
 cd bluespice3
 # switch to 1.31
 git checkout REL1_31
 cd mw-config
 # get installer
 rm -rf overrides
 git clone https://github.com/hallowelt/bluespice-config-mw-overrides overrides
 cd ..
 # init submodules
 git submodule update --init --recursive
 # run composer
 composer update
 # set rights the easy way - don't do that in production!
 chmod a+w * --recursive
 echo "ready for install with WebInstaller"
```

Install with cli:
```
 php maintenance/install.php --dbname DBNAME --dbpass DBPASS --dbserver DBSERVER --dbuser DBUSER --pass hallowelt --server http://localhost --wiki bluespice3 --scriptpath /bluespice3 --with-extensions bluespice3 WikiSysop
```
