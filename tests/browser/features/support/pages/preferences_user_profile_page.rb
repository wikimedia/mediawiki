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
class PreferencesUserProfilePage
  include PageObject

  page_url 'Special:Preferences#mw-prefsection-personal'

  table(:basic_info_table, id: 'mw-htmlform-info')
  link(:change_password_link, text: 'Change password')
  table(:email_table, id: 'mw-htmlform-email')
  radio_button(:gender_female_radio, id: 'mw-input-wpgender-male')
  radio_button(:gender_male_radio, id: 'mw-input-wpgender-female')
  radio_button(:gender_undefined_radio, id: 'mw-input-wpgender-unknown')
  select_list(:lang_select, id: 'mw-input-wplanguage')
  checkbox(:remember_password_check, id: 'mw-input-wprememberpassword')
  text_field(:signature_field, id: 'mw-input-wpnickname')
  table(:signature_table, id: 'mw-htmlform-signature')
end
