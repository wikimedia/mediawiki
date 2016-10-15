#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
This script can be used to change one image to another or remove an image.

Syntax:

    python pwb.py image image_name [new_image_name]

If only one command-line parameter is provided then that image will be removed;
if two are provided, then the first image will be replaced by the second one on
all pages.

Command line options:

-summary:  Provide a custom edit summary.  If the summary includes spaces,
           surround it with single quotes, such as:
           -summary:'My edit summary'
-always    Don't prompt to make changes, just do them.
-loose     Do loose replacements.  This will replace all occurrences of the name
           of the image (and not just explicit image syntax).  This should work
           to catch all instances of the image, including where it is used as a
           template parameter or in image galleries.  However, it can also make
           more mistakes.  This only works with image replacement, not image
           removal.

Examples:

The image "FlagrantCopyvio.jpg" is about to be deleted, so let's first remove it
from everything that displays it:

    python pwb.py image FlagrantCopyvio.jpg

The image "Flag.svg" has been uploaded, making the old "Flag.jpg" obsolete:

    python pwb.py image Flag.jpg Flag.svg

"""
#
# (C) Pywikibot team, 2013-2016
#
# Distributed under the terms of the MIT license.
#
from __future__ import absolute_import, unicode_literals

__version__ = '$Id$'
#
import re

import pywikibot

from pywikibot import i18n, pagegenerators, Bot

from scripts.replace import ReplaceRobot as ReplaceBot


class ImageRobot(ReplaceBot):

    """This bot will replace or remove all occurrences of an old image."""

    def __init__(self, generator, old_image, new_image=None, **kwargs):
        """
        Constructor.

        @param generator: the pages to work on
        @type  generator: iterable
        @param old_image: the title of the old image (without namespace)
        @type  old_image: unicode
        @param new_image: the title of the new image (without namespace), or
                          None if you want to remove the image
        @type  new_image: unicode or None
        """
        self.availableOptions.update({
            'summary': None,
            'loose': False,
        })

        Bot.__init__(self, generator=generator, **kwargs)

        self.old_image = old_image
        self.new_image = new_image
        param = {
            'old': self.old_image,
            'new': self.new_image,
            'file': self.old_image,
        }

        summary = self.getOption('summary') or i18n.twtranslate(
            self.site, 'image-replace' if self.new_image else 'image-remove',
            param)

        namespace = self.site.namespaces[6]
        if namespace.case == 'first-letter':
            case = re.escape(self.old_image[0].upper() +
                             self.old_image[0].lower())
            escaped = '[' + case + ']' + re.escape(self.old_image[1:])
        else:
            escaped = re.escape(self.old_image)

        # Be careful, spaces and _ have been converted to '\ ' and '\_'
        escaped = re.sub('\\\\[_ ]', '[_ ]', escaped)
        if not self.getOption('loose') or not self.new_image:
            image_regex = re.compile(
                r'\[\[ *(?:%s)\s*:\s*%s *(?P<parameters>\|[^\n]+|) *\]\]'
                % ('|'.join(namespace), escaped))
        else:
            image_regex = re.compile(r'' + escaped)

        replacements = []
        if self.new_image:
            if not self.getOption('loose'):
                replacements.append((image_regex,
                                     u'[[%s:%s\\g<parameters>]]'
                                     % (self.site.namespaces.FILE.custom_name,
                                        self.new_image)))
            else:
                replacements.append((image_regex, self.new_image))
        else:
            replacements.append((image_regex, ''))

        super(ImageRobot, self).__init__(self.generator, replacements,
                                         always=self.getOption('always'),
                                         summary=summary)


def main(*args):
    """
    Process command line arguments and invoke bot.

    If args is an empty list, sys.argv is used.

    @param args: command line arguments
    @type args: list of unicode
    """
    old_image = None
    new_image = None
    options = {}

    for arg in pywikibot.handle_args(args):
        if arg == '-always':
            options['always'] = True
        elif arg == '-loose':
            options['loose'] = True
        elif arg.startswith('-summary'):
            if len(arg) == len('-summary'):
                options['summary'] = pywikibot.input(u'Choose an edit summary: ')
            else:
                options['summary'] = arg[len('-summary:'):]
        elif old_image:
            new_image = arg
        else:
            old_image = arg

    if old_image:
        site = pywikibot.Site()
        old_imagepage = pywikibot.FilePage(site, old_image)
        gen = pagegenerators.FileLinksGenerator(old_imagepage)
        preloadingGen = pagegenerators.PreloadingGenerator(gen)
        bot = ImageRobot(preloadingGen, old_image, new_image, site=site  **options)
        bot.run()
        return True
    else:
        pywikibot.bot.suggest_help(missing_parameters=['old image'])
        return False

if __name__ == "__main__":
    main()
