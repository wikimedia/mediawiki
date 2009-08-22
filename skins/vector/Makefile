#
# Handy makefile to build the RTL variant with cssjanus
#

all: main-rtl.css

main-rtl.css: main-ltr.css cssjanus/cssjanus.py
	python cssjanus/cssjanus.py --swap_ltr_rtl_in_url < main-ltr.css > main-rtl.css 

# SVN version is broken; checking in our own.
#cssjanus/cssjanus.py:
#	svn co http://cssjanus.googlecode.com/svn/trunk cssjanus

#distclean: clean
#	rm -rf cssjanus

clean:
	rm -f main-rtl.css
