
MESSAGE_SOURCES=$(wildcard ../languages/Messages*.php)
MESSAGE_TARGETS=$(patsubst ../languages/Messages%.php, Messages%.ser, $(MESSAGE_SOURCES))
SPECIAL_TARGETS=Utf8Case.ser
ALL_TARGETS=$(MESSAGE_TARGETS) $(SPECIAL_TARGETS)
DIST_TARGETS=$(SPECIAL_TARGETS) \
	     MessagesDe.ser \
	     MessagesEn.ser \
	     MessagesFr.ser \
	     MessagesJa.ser \
	     MessagesNl.ser \
	     MessagesPl.ser \
	     MessagesSv.ser

.PHONY: all dist clean

all: $(ALL_TARGETS)

dist: $(DIST_TARGETS)

clean:
	rm -f $(ALL_TARGETS)

Utf8Case.ser : ../includes/Utf8Case.php
	php serialize.php -o $@ $<

Messages%.ser : ../languages/Messages%.php ../languages/MessagesEn.php
	php serialize-localisation.php -o $@ $<
