// document.addEventListener("DOMContentLoaded", function() {
//     const menuButton = document.querySelector('.open-menu-btn');
//     if (menuButton) {
//         menuButton.onclick = togglePanel; // Attach the onclick handler
//     }
// });

function togglePanel() {
    const panel = document.getElementById('panel');
    panel.classList.toggle('active');
}

function changeContent(divId){
    const content = document.querySelectorAll('.content');

    content.forEach(cont => {
        cont.style.display = 'none';
    });

    const selectedContent = document.getElementById(divId);
    selectedContent.style.display = 'block';
}

function searchItems() {
    const query = document.getElementById('search').value.toLowerCase();
    const items = document.querySelectorAll('.conts');

    items.forEach(item => {
        // const uname = item.querySelector('.username').textContent.toLowerCase();
        const brand = item.querySelector('.brand').textContent.toLowerCase();
        const model = item.querySelector('.model').textContent.toLowerCase();
        const productId = item.querySelector('.productId').textContent.toLowerCase();

        // Check if the query matches any part of the product details
        if (brand.includes(query) || model.includes(query) || productId.includes(query)) {
            item.style.display = 'block'; // Show item
        } else {
            item.style.display = 'none'; // Hide item
        }
    });
}

function searchItems2() {
    const query = document.getElementById('search2').value.toLowerCase();
    const itemsAll = document.querySelectorAll('.contsAll');

    itemsAll.forEach(item2 => {
        const uname = item2.querySelector('.username2').textContent.toLowerCase();
        const brand = item2.querySelector('.brand2').textContent.toLowerCase();
        const model = item2.querySelector('.model2').textContent.toLowerCase();
        const productId = item2.querySelector('.productId2').textContent.toLowerCase();

        // Check if the query matches any part of the product details
        if (brand.includes(query) || model.includes(query) || productId.includes(query) || uname.includes(query)) {
            item2.style.display = 'block'; // Show item
        } else {
            item2.style.display = 'none'; // Hide item
        }
    });
}

function searchUsers() {
    const query = document.getElementById('search').value.toLowerCase();
    const items = document.querySelectorAll('.conts');

    items.forEach(item => {
        const name = item.querySelector('.name').textContent.toLowerCase();
        const username = item.querySelector('.username').textContent.toLowerCase();
        const email = item.querySelector('.email').textContent.toLowerCase();
        const type = item.querySelector('.type').textContent.toLowerCase();
        const typeOfShop = item.querySelector('.typeOfShop').textContent.toLowerCase();
        const address = item.querySelector('.address').textContent.toLowerCase();

        // Check if the query matches any part of the product details
        if (name.includes(query) || username.includes(query) || email.includes(query) || type.includes(query) || typeOfShop.includes(query) || address.includes(query)) {
            item.style.display = 'block'; // Show item
        } else {
            item.style.display = 'none'; // Hide item
        }
    });
}

function toggleDropdown() {
    // const dropdown = event.target.nextElementSibling; // Get the dropdown div
    const dropdown = document.querySelector(".dropdownEdit");
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle visibility
}
function toggleDropdown2() {
    // const dropdown = event.target.nextElementSibling; // Get the dropdown div
    const dropdown = document.querySelector(".dropdownEdit2");
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block'; // Toggle visibility
}

function showInputField() {
    const inputFields = document.querySelectorAll('.inputField'); // Select all input fields
    inputFields.forEach(input => {
        input.style.display = 'block'; // Show each input field
    });
}
function showInputField2() {
    const inputFields = document.querySelectorAll('.inputField2'); // Select all input fields
    inputFields.forEach(input => {
        input.style.display = 'block'; // Show each input field
    });
}

function toggleModal() {
    const modal = document.getElementById("confirmModal");
    modal.style.display = modal.style.display === "none" ? "flex" : "none";
}
function toggleModal2() {
    const modal = document.getElementById("confirmModal2");
    modal.style.display = modal.style.display === "none" ? "flex" : "none";
}

function toggleModal3(productId) {
    productIdToDelete = productId;
    document.getElementById("modalProductId").value = productId; // Set the productId in the modal
    const modal = document.getElementById("confirmModal");
    modal.style.display = modal.style.display === "none" ? "flex" : "none";
}

function toggleModal4(username) {
    userToDelete = username;
    document.getElementById("modalUsernameShopk").value = username; // Set the username in the modal
    const modal = document.getElementById("confirmModal2");
    modal.style.display = modal.style.display === "none" ? "flex" : "none";
}

function toggleModal5(username) {
    userToDelete = username;
    document.getElementById("modalUsernameCust").value = username; // Set the username in the modal
    const modal = document.getElementById("confirmModal2");
    modal.style.display = modal.style.display === "none" ? "flex" : "none";
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
}
function closeModal2() {
    document.getElementById("confirmModal2").style.display = "none";
}                
