class EditPage
  include PageObject

  text_area(:edit_page_content, id: 'wpTextbox1')
  button(:preview_button, id: 'wpPreview')
  button(:show_changes_button, id: 'wpDiff')
  button(:save_button, id: 'wpSave')
end
