document.addEventListener('DOMContentLoaded', () => {
    const sortDropdown = document.getElementById('sort');
    const tableBody = document.querySelector('.table-wrapper tbody');
    const searchTypeDropdown = document.getElementById('searchType');
    const inputContainer = document.getElementById('inputContainer');

    if (!sortDropdown || !tableBody) {
        console.error('Sort dropdown or table body not found.');
    }

    // Function to render the appropriate input field based on the search type
    const renderInputField = (type) => {
        let inputHTML = '';

        switch (type) {
            case 'name':
                inputHTML = `
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="input-style" placeholder="Enter grant name" required>
                `;
                break;
            case 'open_date':
                inputHTML = `
                    <label for="open_date">Open Date:</label>
                    <input type="date" id="open_date" name="open_date" class="input-style" required>
                `;
                break;
            case 'due_date':
                inputHTML = `
                    <label for="due_date">Close Date:</label>
                    <input type="date" id="due_date" name="due_date" class="input-style" required>
                `;
                break;
            case 'funder':
                inputHTML = `
                    <label for="funder">Funder:</label>
                    <input type="text" id="funder" name="funder" class="input-style" placeholder="Enter funder name" required>
                `;
                break;
            case 'category':
                inputHTML = `
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" class="input-style" placeholder="Enter category" required>
                `;
                break;
            default:
                inputHTML = ''; // No input for default state
        }

        inputContainer.innerHTML = inputHTML; // Update the container with the new input field
    };

    // Listen for changes on the search type dropdown
    if (searchTypeDropdown) {
        searchTypeDropdown.addEventListener('change', (event) => {
            renderInputField(event.target.value); // Update the input field dynamically
        });

        // Initialize the input field based on the default selection
        renderInputField(searchTypeDropdown.value);
    }

    // Function to compare rows for sorting
    function compareRows(a, b, columnIndex, isDate = false) {
        const getValue = (row, index) => row.children[index].textContent.trim();
        let valueA = getValue(a, columnIndex);
        let valueB = getValue(b, columnIndex);

        if (isDate) {
            valueA = new Date(valueA);
            valueB = new Date(valueB);
            return valueA - valueB;
        } else {
            return valueA.localeCompare(valueB, undefined, { sensitivity: 'base' });
        }
    }

    // Function to sort rows in the table
    function sortTable(columnIndex, isDate = false) {
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        if (rows.length === 0) {
            console.warn('No rows to sort.');
            return;
        }

        rows.sort((rowA, rowB) => compareRows(rowA, rowB, columnIndex, isDate));

        tableBody.innerHTML = '';
        rows.forEach(row => tableBody.appendChild(row));
    }

    // Event listener for sorting dropdown
    if (sortDropdown) {
        sortDropdown.addEventListener('change', () => {
            const selectedOption = sortDropdown.value;

            switch (selectedOption) {
                case 'name':
                    sortTable(0); // Sort by Grant Name (Column 0)
                    break;
                case 'open_date':
                    sortTable(1, true); // Sort by Open Date (Column 1, as Date)
                    break;
                case 'due_date':
                    sortTable(2, true); // Sort by Close Date (Column 2, as Date)
                    break;
                case 'funder':
                    sortTable(3); // Sort by Funder (Column 3)
                    break;
                default:
                    console.warn('Invalid sorting option selected.');
            }
        });
    }

    // Function to make rows clickable
    function makeRowsClickable() {
        const rows = document.querySelectorAll('.table-wrapper tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function () {
                const href = row.getAttribute('data-href');
                if (href) {
                    window.location.href = href;
                }
            });
        });
    }

    makeRowsClickable();
});
