Given(/^I go to Create account page at (.+)$/) do |path|
  visit(CreateAccountPage, using_params: { page_title: path })
end

Then(/^form has Create account button$/) do
  expect(on(CreateAccountPage).create_account_element).to exist
end

When(/^I submit the form$/) do
  on(CreateAccountPage).create_account
end

Then(/^an error message is displayed$/) do
  expect(on(CreateAccountPage).error_message_element.class_name).to eq 'errorbox'
end
