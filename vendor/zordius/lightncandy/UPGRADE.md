Upgrade Notice
==============

* Standalone templates compiled by older LightnCandy can be executed safe when you upgrade to any new version of LightnCandy.

* Recompile your none standalone templates when you upgrade LightnCandy.

CURRENT MASTER
--------------
* Option FLAG_MUSTACHESEC removed, no need to use this flag anymore.

Version v0.13
-------------
* The interface of custom helpers was changed from v0.13 . if you use this feature you may need to modify your custom helper functions.

Version v0.11
-------------
* Due to big change of render() debugging, the rendering support class `LCRun2` is renamed to `LCRun3`. If you compile templates as none standalone PHP code by LightnCandy v0.11 or before, you should compile these templates again. Or, you may run into `Class 'LCRun2' not found` error when you execute these old rendering functions.

Version v0.9
------------
* Due to big change of variable name handling, the rendering support class `LCRun` is renamed to `LCRun2`. If you compile templates as none standalone PHP code by LightnCandy v0.9 or before, you should compile these templates again. Or, you may run into `Class 'LCRun' not found` error when you execute these old rendering functions.
