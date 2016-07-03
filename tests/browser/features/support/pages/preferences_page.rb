class PreferencesPage
  include PageObject

  page_url 'Special:Preferences'

  a(:appearance_link, id: 'preftab-rendering')
  a(:editing_link, id: 'preftab-editing')
  a(:user_profile_link, id: 'preftab-personal')
  button(:save_button, id: 'prefcontrol')
end
