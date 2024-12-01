document.addEventListener('DOMContentLoaded', function () {
    const addFieldButton = document.getElementById('add-field');
    const removeFieldButton = document.getElementById('remove-field');
    const fieldsContainer = document.getElementById('fields-container');
    let fieldIndex = 1; // Start from index 1 since index 0 is the initial field

    addFieldButton.addEventListener('click', addField);
    removeFieldButton.addEventListener('click', removeField);

    // Load saved fields from localStorage if available
    loadFieldsFromStorage();

    // Function to add a new field dynamically with animation
    function addField() {
        const fieldTemplate = document.querySelector('.field-entry');
        const newField = fieldTemplate.cloneNode(true);

        // Update the names of the cloned fields dynamically
        newField.querySelectorAll('input, select').forEach(input => {
            let name = input.name.replace(/\[0\]/, `[${fieldIndex}]`);
            input.name = name;

            if (input.type === 'checkbox') {
                input.checked = false; // Reset checkboxes
            } else {
                input.value = ''; // Clear other input values
            }
        });

        fieldsContainer.appendChild(newField);

        // Add the visible class for animation
        setTimeout(() => {
            newField.classList.add('visible');
        }, 10); // Delay slightly for animation to kick in

        // Save the updated fields to localStorage
        saveFieldsToStorage();

        fieldIndex++; // Increment the field index for the next field
    }

    // Function to remove the last dynamically added field with animation
    function removeField() {
        const lastField = fieldsContainer.querySelector('.field-entry:last-child');

        if (lastField && lastField !== fieldsContainer.firstElementChild) {
            // Add removing class before deletion
            lastField.classList.add('removing');

            // Wait for the animation to finish, then remove the element
            lastField.addEventListener('transitionend', () => {
                fieldsContainer.removeChild(lastField);
                // Save the updated fields to localStorage after removal
                saveFieldsToStorage();
                fieldIndex--; // Decrement the field index
            });
        }
    }

    // Save fields to localStorage
    function saveFieldsToStorage() {
        const fieldsData = [];
        fieldsContainer.querySelectorAll('.field-entry').forEach(field => {
            const fieldValues = {};
            field.querySelectorAll('input, select').forEach(input => {
                if (input.type === 'checkbox') {
                    fieldValues[input.name] = input.checked; // Save checkbox state
                } else {
                    fieldValues[input.name] = input.value; // Save other input values
                }
            });
            fieldsData.push(fieldValues);
        });

        // Store the fields in localStorage
        localStorage.setItem('formFields', JSON.stringify(fieldsData));
    }

    // Load fields from localStorage
    function loadFieldsFromStorage() {
        const savedFields = localStorage.getItem('formFields');
        if (savedFields) {
            const fieldsData = JSON.parse(savedFields);
            fieldsData.forEach((fieldData, index) => {
                if (index > 0) {
                    addField(); // Add a new field if more than the initial one
                }

                const field = fieldsContainer.querySelectorAll('.field-entry')[index];
                if (field) {
                    // Populate the fields with saved data
                    Object.keys(fieldData).forEach(name => {
                        const input = field.querySelector(`[name="${name}"]`);
                        if (input) {
                            if (input.type === 'checkbox') {
                                input.checked = fieldData[name]; // Restore checkbox state
                            } else {
                                input.value = fieldData[name]; // Restore other input values
                            }
                        }
                    });
                }
            });
        }
    }
});
