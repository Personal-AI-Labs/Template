document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('profile-dropdown-toggle');
    const dropdownMenu = document.getElementById('profile-dropdown-menu');

    if (toggleButton && dropdownMenu) {
        // Toggle dropdown on button click
        toggleButton.addEventListener('click', function (event) {
            event.stopPropagation(); // Prevent the click from bubbling up to the window
            dropdownMenu.classList.toggle('show');
        });

        // Close dropdown if clicked outside
        window.addEventListener('click', function (event) {
            if (!dropdownMenu.contains(event.target) && !toggleButton.contains(event.target)) {
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                }
            }
        });
    }
});