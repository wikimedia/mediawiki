#
# This file is subject to the license terms in the LICENSE file found in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/LICENSE. No part of
# qa-browsertests, including this file, may be copied, modified, propagated, or
# distributed except according to the terms contained in the LICENSE file.
#
# Copyright 2012-2014 by the Mediawiki developers. See the CREDITS file in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/CREDITS
#
class PreferencesPage
  include PageObject

  page_url 'Special:Preferences'

  a(:appearance_link, id: 'preftab-rendering')
  a(:editing_link, id: 'preftab-editing')
  a(:user_profile_link, id: 'preftab-personal')
  button(:save_button, id: 'prefcontrol')
end
