
SPECIAL_TARGETS=Utf8Case.ser
ALL_TARGETS=$(SPECIAL_TARGETS)
DIST_TARGETS=$(SPECIAL_TARGETS)

.PHONY: all dist clean

all: $(ALL_TARGETS)
	@echo 'Warning: messages are no longer serialized by this makefile.'

dist: $(DIST_TARGETS)

clean:
	rm -f $(ALL_TARGETS)

Utf8Case.ser : ../includes/normal/Utf8Case.php
	php serialize.php -o $@ $<

