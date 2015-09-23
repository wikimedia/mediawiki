When(/^I click Edit$/) do
  on(MainPage).edit_link
end

When(/^I click Preview$/) do
  on(EditPage).preview_button
end

When(/^I click Show Changes$/) do
  on(EditPage).show_changes_button
end

When(/^I edit the page with "(.*?)"$/) do |edit_content|
  on(EditPage).edit_page_content_element.send_keys(edit_content + @random_string)
end

When(/^I save the edit$/) do
  on(EditPage).save_button
end

Then(/^the edited page content should contain "(.*?)"$/) do |content|
  expect(on(MainPage).page_content).to match(content + @random_string)
end
