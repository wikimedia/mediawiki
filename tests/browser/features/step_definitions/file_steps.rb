Given(/^I am at file that does not exist$/) do
  visit(FileDoesNotExistPage, using_params: { page_name: @random_string })
end

Then(/^page should show that no such file exists$/) do
  expect(on(FileDoesNotExistPage).file_does_not_exist_message_element).to be_visible
end
