function addLink() {
    const linkContainer = document.getElementById('link-container');
  
    // Create elements with appropriate classes and attributes
    const newLinkDiv = document.createElement('div');
    newLinkDiv.classList.add('link-div');
  
    // Label for link
    const newLabel = document.createElement('label');
    newLabel.textContent = "Link:";
  
    // Title input
    const newTitleInput = document.createElement('input');
    newTitleInput.type = 'text';
    newTitleInput.placeholder = 'Enter link title';
    newTitleInput.name = `link_title_${linkContainer.childElementCount + 1}`;
  
    // URL input
    const newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.placeholder = 'Enter link URL';
    newInput.name = `link_url_${linkContainer.childElementCount + 1}`;
  
    const removeButton = document.createElement('button');
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