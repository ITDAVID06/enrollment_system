const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const mainContent = document.querySelector(".main-content");
const collapsedSidebarWidth = "5.3125em";
const fullSidebarWidth = "16.875em";

// Toggle sidebar collapse
sidebarToggler.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");

    mainContent.classList.toggle("collapsed-sidebar", sidebar.classList.contains("collapsed"));
    mainContent.classList.toggle("expanded-sidebar", !sidebar.classList.contains("collapsed"));
});
// Handle menu toggle for mobile responsiveness
menuToggler.addEventListener("click", () => {
    toggleMenu(sidebar.classList.toggle("menu-active"));
});

const toggleMenu = (isMenuActive) => {
    sidebar.style.height = isMenuActive ? `${sidebar.scrollHeight}px` : "";
    menuToggler.querySelector("span").innerText = isMenuActive ? "close" : "menu";
};

// Handle window resize for responsiveness
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        sidebar.style.height = "calc(100vh - 2em)";
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
    }
});
