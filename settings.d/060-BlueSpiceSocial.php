<?php
/*
	BSSocial require elastic search to be ready otherwise the installation will not work
	If your system setup is ready for elastic and the new bluespiceextendedsearch copy this file to
	060-BlueSpiceSocial.local.php with the following line activated. The .local.php File stay untouched
	on git and composer updates.

	Prerequisite for the new extended search is;
	php extensions/BlueSpiceExtendedSearch/maintenance/initBackends.php --quick
	php extensions/BlueSpiceExtendedSearch/maintenance/rebuildIndex.php --quick
	php /media/build/tests/bluespice_pro_master/maintenance/runJobs.php
 */
wfLoadExtension( 'BlueSpiceSocial' );
wfLoadExtension( 'BlueSpiceSocialArticleActions' );
wfLoadExtension( 'BlueSpiceSocialBlog' );
wfLoadExtension( 'BlueSpiceSocialComments' );
wfLoadExtension( 'BlueSpiceSocialMicroBlog' );
wfLoadExtension( 'BlueSpiceSocialRating' );
wfLoadExtension( 'BlueSpiceSocialTimelineUpdate' );
wfLoadExtension( 'BlueSpiceSocialProfile' );
wfLoadExtension( 'BlueSpiceSocialWikiPage' );
wfLoadExtension( 'BlueSpiceSocialGroups' );
wfLoadExtension( 'BlueSpiceSocialTags' );
wfLoadExtension( 'BlueSpiceSocialWatch' );
wfLoadExtension( 'BlueSpiceSocialTopics' );