function addField() {
    const linkContainer = document.getElementById('dynField-container');
  
    // Create elements with appropriate classes and attributes
    const newLinkDiv = document.createElement('div');
    newLinkDiv.classList.add('link-div');
  
    // Label for link
    const newLabel = document.createElement('label');
    newLabel.textContent = "Field:";
  
    // Title input
    const newTitleInput = document.createElement('input');
    newTitleInput.type = 'text';
    newTitleInput.placeholder = 'Enter field title';
    newTitleInput.name = `link_title_${linkContainer.childElementCount + 1}`;
    newTitleInput.id = `link_title_${linkContainer.childElementCount + 1}`;
  
    // URL input
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.placeholder = 'Enter field data';
    newInput.name = `link_url_${linkContainer.childElementCount + 1}`;
    newInput.id = `link_url_${linkContainer.childElementCount + 1}`;
  
    const removeButton = document.createElement('link-tag');
    removeButton.textContent = 'X';
    removeButton.classList.add('remove-button');
    removeButton.addEventListener('click', () => linkContainer.removeChild(newLinkDiv));
  
    // Add a line break after the remove button
    const lineBreak = document.createElement('br');
  
    // Append elements to the div
    newLinkDiv.append(newLabel, newTitleInput, newInput, removeButton, lineBreak);
  
    // Append the div to the container
    linkContainer.appendChild(newLinkDiv);
  }