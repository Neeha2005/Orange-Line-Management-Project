
function openMapModal(lat, lng, name) {
    console.log("Opening map for:", name);

    const modal = document.getElementById("mapModal");
    const title = document.getElementById("mapTitle");
    const iframe = document.getElementById("mapFrame");

    if (modal && title && iframe) {
        const mapURL = `https://www.google.com/maps?q=${lat},${lng}&hl=es;z=14&output=embed`;
        iframe.src = mapURL;
        title.textContent = name;
        modal.classList.remove("hidden");
    } else {
        console.warn("Map modal elements not found.");
    }
}

function closeMapModal() {
    const modal = document.getElementById("mapModal");
    const iframe = document.getElementById("mapFrame");

    if (modal && iframe) {
        iframe.src = ""; // Reset map
        modal.classList.add("hidden");
    }
}

window.openMapModal = openMapModal;
window.closeMapModal = closeMapModal;

document.addEventListener("DOMContentLoaded", () => {
    // Handle Login Button
    const loginBtn = document.querySelector(".login-btn");
    if (loginBtn) {
        loginBtn.addEventListener("click", () => {
            window.location.href = 'login.php';
        });
    }

    // Handle Register Button
    const registerBtn = document.querySelector(".register-btn");
    if (registerBtn) {
        registerBtn.addEventListener("click", () => {
            window.location.href = 'Register.php';
        });
    }
});
