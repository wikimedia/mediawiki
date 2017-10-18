# Firejail profile used by MediaWiki when shelling out
# See <https://firejail.wordpress.com/features-3/man-firejail-profile/> for
# syntax documentation
# Persistent global definitions
include /etc/firejail/globals.local

# block access to system directories
blacklist /sbin
blacklist /usr/sbin
blacklist /usr/local/sbin

# block access to system management utilities
blacklist ${PATH}/umount
blacklist ${PATH}/mount
blacklist ${PATH}/fusermount
blacklist ${PATH}/su
blacklist ${PATH}/sudo
blacklist ${PATH}/xinput
blacklist ${PATH}/evtest
blacklist ${PATH}/xev
blacklist ${PATH}/strace
blacklist ${PATH}/nc
blacklist ${PATH}/ncat

blacklist /etc/shadow
blacklist /etc/ssh
blacklist /root
noroot
caps.drop all
seccomp
private-dev
