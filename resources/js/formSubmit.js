document.getElementById('form').addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevent the default form submission

    const form = e.target;
    const formFields = [];

    // Collecting all form fields data
    document.querySelectorAll('.form-field-group').forEach(function (fieldGroup) {
        const fieldInput = fieldGroup.querySelector('input, textarea, select');
        const label = fieldGroup.querySelector('label');
        const fieldId = fieldInput?.dataset.fieldId; // Get the field's custom ID (data-field-id)
        const fieldValue = fieldInput?.value; // Get the value of the field
        const labelText = label ? label.textContent : 'Champ'; // Get the label's text content or default to 'Field'

        // If the field has a fieldId, push it to the formFields array
        if (fieldId) {
            formFields.push({
                form_field_id: fieldId,
                question: labelText, // Use label text for the question
                answer: fieldValue || 'Aucune réponse fournie', // Default is 'No answer provided'
            });
        }
    });

    // Populate the modal with the user's answers
    const reviewList = document.getElementById('reviewList');
    reviewList.innerHTML = ''; // Clear previous entries
    formFields.forEach(field => {
        const listItem = document.createElement('li');
        listItem.classList.add('py-2', 'px-4', 'border-b', 'border-gray-200', 'text-gray-800', 'fade-in'); // Added fade-in animation
        listItem.innerHTML = `<strong>${field.question} :</strong> <span class="text-blue-600">${field.answer}</span>`;
        reviewList.appendChild(listItem);
    });

    // Show the review modal with animation
    const reviewModal = document.getElementById('reviewModal');
    reviewModal.classList.remove('hidden');
    reviewModal.classList.add('fade-in-modal');  // Added fade-in effect for the modal
    
    // // Disable page interaction while modal is active
    // document.body.style.pointerEvents = 'auto';  

    // Handle modal buttons
    document.getElementById('editButton').addEventListener('click', function () {
        reviewModal.classList.add('hidden'); // Hide the modal to allow editing
        document.body.style.pointerEvents = 'auto';  // Enable interactions again
    });

    document.getElementById('confirmButton').addEventListener('click', async function () {
        // Close the modal with fade-out animation
        reviewModal.classList.remove('fade-in-modal');
        reviewModal.classList.add('fade-out-modal');
        setTimeout(() => {
            reviewModal.classList.add('hidden');  // Hide modal after animation
            document.body.style.pointerEvents = 'auto'; // Enable interactions again
        }, 300);  // Wait for the fade-out animation to complete

        // Send the form data to the server
        const formDataJson = {
            form_id: form.dataset.formId,
            email: document.querySelector('input[type=email]')?.value || '',
            fields: formFields.map(({ form_field_id, answer }) => ({ form_field_id, answer })),
        };

        const spinner = document.getElementById('spinner');
        const responseMessage = document.getElementById('responseMessage');
        spinner.style.display = 'block';
        responseMessage.style.display = 'none';

        try {
            const response = await fetch('/submit-form', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(formDataJson),
            });

            const result = await response.json();
            spinner.style.display = 'none';

            if (response.ok) {
                responseMessage.innerHTML = `
                    <div class="bg-green-500 text-white p-4 rounded-md shadow-md flex items-center">
                        <i class="fa fa-check-circle mr-2"></i>
                        <span>${result.message}</span>
                    </div>
                `;
                responseMessage.style.display = 'block';
                form.reset();
            } else {
                responseMessage.innerHTML = `
                    <div class="bg-red-500 text-white p-4 rounded-md shadow-md items-center">
                        <i class="fa fa-times-circle mr-2"></i>
                        <span>Erreur : ${result.error} </span><br>
                        <span>Détails : ${result.details}</span>
                    </div>
                `;
                responseMessage.style.display = 'block';
            }
        } catch (error) {
            spinner.style.display = 'none';
            responseMessage.innerHTML = `
                <div class="bg-yellow-500 text-white p-4 rounded-md shadow-md flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>Une erreur inattendue est survenue. Veuillez réessayer plus tard.</span>
                </div>
            `;
            responseMessage.style.display = 'block';
        }
    });
});
