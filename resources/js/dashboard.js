document.addEventListener('DOMContentLoaded', function () {
    // Adding event listeners to all copy link buttons
    const copyButtons = document.querySelectorAll('.copy-link-btn');

    copyButtons.forEach(function(button) {
        button.addEventListener('click', function (event) {
            // Get the link from the button's data attribute
            const link = event.target.getAttribute('data-link');
            copyLink(event, link);
        });
    });
});

function copyLink(event, link) {
    // Create a temporary input element to copy the link
    var input = document.createElement('input');
    input.setAttribute('value', link);
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);

    // Select the span inside the button
    const span = event.target.querySelector('span');

    if (span) {
        // Store the original text of the span
        const originalText = span.innerHTML;

        // Replace span text with "Link copied!" message
        span.innerHTML = 'Link copied!';

        // Change the button style for feedback
        event.target.classList.add('bg-green-600', 'text-white'); // Change button color for feedback

        // Revert back the original span text after 1.5 seconds
        setTimeout(() => {
            span.innerHTML = originalText;
            event.target.classList.remove('bg-green-600', 'text-white'); // Reset button color
        }, 1500);
    }
}
