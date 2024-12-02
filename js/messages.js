function sendDelete(id) {
    sendAJAXRequest('deleteNotification.php?id=' + id, { }, deleteCallback);
}

function deleteCallback() {
    let response = JSON.parse(this.responseText);
    if (response.result) {
        window.location = 'inbox.php?deleteSuccess';
    }
}

function sendAJAXRequest(url, requestData, onSuccess, onFailure) {
    var request = new XMLHttpRequest();
    request.open("POST", url, true);
    request.setRequestHeader("Content-Type", "application/json");
    request.onload = onSuccess;
    request.onerror = onFailure;
    request.send(JSON.stringify(requestData));
    return false;
}

$(function() {
    $('tr.message').click(function() {
        let id = $(this).data('message-id');
        window.location = 'viewNotification.php?id=' + id;
    });

    $('#delete-button').click(function() {
        let id = $(this).data('message-id');
        sendDelete(id);
    });
});

function markAllAsRead() {
    // Make AJAX request
    fetch('markAllAsRead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${encodeURIComponent(userID)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the titles dynamically
                document.querySelectorAll('.unread').forEach(row => {
                    row.classList.remove('unread'); // Remove unread styling
                    const titleCell = row.querySelector('td:nth-child(2)');
                    titleCell.textContent = titleCell.textContent.replace(/\(!\)\s*/, ''); // Remove the "(!)"
                });

                // Update the URL without reloading
                const newUrl = new URL(window.location.href);
                newUrl.searchParams.delete('createNotifSuccess');
                newUrl.searchParams.delete('deleteNotifSuccess');
                newUrl.searchParams.delete('markAllAsReadSuccess');
                newUrl.searchParams.append('markAllAsReadSuccess', '');
                window.history.pushState(null, '', newUrl);

                // Dynamically show the "happy-toast" banner
                const existingBanner = document.querySelector('.happy-toast');
                if (existingBanner) {
                    existingBanner.textContent = 'All notifications marked as read.';
                } else {
                    const banner = document.createElement('div');
                    banner.className = 'happy-toast';
                    banner.textContent = 'All notifications marked as read.';
                    const header = document.querySelector('h2');
                    header.parentNode.insertBefore(banner, header);
                }

                // Hide the delete confirmation popup
                const deleteConfirmationWrapper = document.getElementById('delete-confirmation-wrapper');
                deleteConfirmationWrapper.classList.add('hidden');
                fetchMessages('priority');
            } else {
                
                alert('Failed to mark notifications as read: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
            alert('An error occurred while processing your request.');
        });
}

function fetchMessages(sortOrder) {
    fetch('fetchMessages.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `sortOrder=${encodeURIComponent(sortOrder)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the table with the new data
                updateTable(data.messages);
            } else {
                alert('Error fetching messages: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
            alert('An error occurred while fetching messages.');
        });
}

function makeRowsClickable() {
    const rows = document.querySelectorAll('.table-wrapper tbody tr');
    rows.forEach(row => {
        row.addEventListener('click', function() {
            // Handle row click
            const messageId = row.dataset.messageId;
            window.location.href = `viewNotification.php?id=${messageId}`; // Or any other action you want
        });
    });
}

function updateTable(messages) {
    const tableBody = document.querySelector('.table-wrapper tbody');
    tableBody.innerHTML = ''; // Clear the existing rows

    // Loop through the sorted messages and create new rows
    messages.forEach(message => {
        const { senderID, title, time, wasRead, prioritylevel } = message;

        // Create a new row
        const row = document.createElement('tr');
        row.className = `message ${wasRead ? '' : 'unread'} prio${prioritylevel}`;
        row.dataset.messageId = message.id;

        const sender = (senderID === 'vmsroot') ? 'System' : senderID;
        const wasReadNumber = Number(wasRead);

        // Check if the message was read or not, and add "(!)" if unread
        let displayTitle = title; // Default to title first
        if (wasReadNumber === 0) {
            displayTitle = `(!) ${title}`;  // Add "(!)" for unread messages
        }

        // Set the inner HTML for the new row
        row.innerHTML = `
            <td>${sender}</td>
            <td>${displayTitle}</td>
            <td>${time}</td>
        `;

        row.style.color = 'white';

        // Append the new row to the table body
        tableBody.appendChild(row);

        makeRowsClickable();
    });
}

// This function updates the button text based on the selected option
function updatePlaceholder(select) {
    const selectedText = select.options[select.selectedIndex].text;
    const button = select.closest('.sortby-style').querySelector('.btn');
    button.querySelector('.filter-option').textContent = "Sort By: " + selectedText;
}