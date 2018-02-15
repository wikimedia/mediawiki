# Firejail profile used by MediaWiki when shelling out
# See <https://firejail.wordpress.com/features-3/man-firejail-profile/> for
# syntax documentation
# Persistent local customizations
include /etc/firejail/mediawiki.local
# Persistent global definitions
include /etc/firejail/globals.local
