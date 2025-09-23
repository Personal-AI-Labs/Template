document.addEventListener('DOMContentLoaded', function() {
    // --- Hamburger Menu Handler ---
    const hamburgerButton = document.getElementById('hamburger-menu');
    const navLinksContainer = document.getElementById('nav-links-container');

    if (hamburgerButton && navLinksContainer) {
        hamburgerButton.addEventListener('click', function () {
            navLinksContainer.classList.toggle('open');
            hamburgerButton.classList.toggle('active');
            const isExpanded = hamburgerButton.getAttribute('aria-expanded') === 'true';
            hamburgerButton.setAttribute('aria-expanded', !isExpanded);
        });
    }

    // --- All Dropdown Logic ---
    const profileDropdownToggle = document.getElementById('profile-dropdown-toggle');
    const profileDropdownMenu = document.getElementById('profile-dropdown-menu');
    const mainMenuDropdownToggles = document.querySelectorAll('.main-menu .dropdown-toggle');

    // Handler for the Profile Dropdown
    if (profileDropdownToggle) {
        profileDropdownToggle.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent window listener from closing it immediately

            // Close any other open dropdowns
            closeAllDropdowns(profileDropdownToggle);

            // Toggle the profile dropdown
            profileDropdownMenu.classList.toggle('show');
            profileDropdownToggle.classList.toggle('active');
        });
    }

    // Handler for Main Menu Dropdowns (e.g., Projects)
    mainMenuDropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent window listener from closing it immediately
            const parentDropdown = this.parentElement;

            // Close any other open dropdowns
            closeAllDropdowns(parentDropdown);

            // Toggle the current dropdown
            parentDropdown.classList.toggle('active');
        });
    });

    // Single "Click Outside" Handler to close all dropdowns
    window.addEventListener('click', function() {
        closeAllDropdowns();
    });

    /**
     * Helper function to close all open dropdowns, with an optional element to ignore.
     * @param {HTMLElement|null} exceptThisElement - The dropdown element that should not be closed.
     */
    function closeAllDropdowns(exceptThisElement = null) {
        if (profileDropdownMenu && profileDropdownToggle.parentElement === exceptThisElement) {
            profileDropdownMenu.classList.remove('show');
            profileDropdownToggle.classList.remove('active');
        }

        document.querySelectorAll('.main-menu .dropdown').forEach(dropdown => {
            if (dropdown !== exceptThisElement) {
                dropdown.classList.remove('active');
            }
        });
    }
});