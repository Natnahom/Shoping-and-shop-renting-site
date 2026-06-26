document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.querySelector('.toggle_btn');
    const toggleBtnIcon = document.querySelector('.toggle_btn i');
    const dropDownMenu = document.querySelector('.dropdown-menu');

    // Check if the elements exist before adding the click event
    if (toggleBtn && toggleBtnIcon && dropDownMenu) {
        toggleBtn.onclick = function () {
            dropDownMenu.classList.toggle('open');
            const isOpen = dropDownMenu.classList.contains('open');

            toggleBtnIcon.className = isOpen
                ? "bx bx-x"
                : "bx bx-menu";
        };
    }
});

        