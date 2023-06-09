TODO
====

Currently the `FeatureManager` class is a very shallow interpretation of Piotr Miazga's proposed
API and associated scaffolding classes (see https://phabricator.wikimedia.org/T244481 and
https://gerrit.wikimedia.org/r/#/c/mediawiki/skins/Vector/+/572323/). This document aims to list
the steps required to get from this system to something as powerful as Piotr's.

1. Consider supporing memoization of those requirements (see https://gerrit.wikimedia.org/r/#/c/mediawiki/skins/Vector/+/573626/7/includes/FeatureManagement/FeatureManager.php@68)
2. Add support for getting all requirements
3. Add support for getting all features enabled when a requirement is enabled/disabled
