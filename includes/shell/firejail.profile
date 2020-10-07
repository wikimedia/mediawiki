# Firejail profile used by MediaWiki when shelling out
# Most rules are applied via command-line flags controlled by the
# Shell::RESTRICTION_* constants.
# Rules added to this file must be compatible with every command that could
# be invoked. If something might need to be disabled, then it should be added
# as a Shell:RESTRICTION_* constant instead so that commands can opt-in/out.

# See <https://firejail.wordpress.com/features-3/man-firejail-profile/> for
# syntax documentation.

# Optionally allow sysadmins to set extra restrictions that apply to their
# MediaWiki setup, e.g. disallowing access to extra private directories.
include /etc/firejail/mediawiki.local

# Include any global firejail customizations.
include /etc/firejail/globals.local

# Drop all capabilities
caps.drop all

# Disallow system directories
blacklist /sbin
blacklist /usr/sbin
blacklist /usr/local/sbin

# Blacklist /run which typically contains many exploitable UNIX sockets. But
# don't blacklist /run/firejail which firejail needs. Using a glob means that
# the files and directories under /run are mounted separately, which allows
# the noblacklist directive to work. (T262364)
noblacklist /run/firejail
blacklist /run/*

# Disallow system files
blacklist /etc/shadow
blacklist /etc/ssh
blacklist /root

# Disallow system management
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
