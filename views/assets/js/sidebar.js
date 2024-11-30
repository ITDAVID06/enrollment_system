

const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const mainContent = document.querySelector(".main-content");
const collapsedSidebarWidth = "5.3125em";
const fullSidebarWidth = "16.875em";

// console.log(document.querySelector(".sidebar")); 

const navLinks = document.querySelectorAll(".nav-link");

    // Function to remove 'active' from all links
    const removeActiveClass = () => {
        navLinks.forEach(link => link.classList.remove("active"));
    };

    // Add click event listener to each nav link
    navLinks.forEach(link => {
        link.addEventListener("click", () => {
            removeActiveClass(); // Remove 'active' from all links
            link.classList.add("active"); // Add 'active' to the clicked link
        });
    });

    // Mark the current page's link as active
    const currentPath = window.location.pathname;
    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });

// Toggle sidebar collapse
sidebarToggler.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");

    // if (sidebar.classList.contains("collapsed")) {
    //     mainContent.style.marginLeft = collapsedSidebarWidth;
    // } else {
    //     mainContent.style.marginLeft = fullSidebarWidth;
    // }
    mainContent.classList.toggle("collapsed-sidebar", sidebar.classList.contains("collapsed"));
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
        sidebar.style.height = "100vh";
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
    }
});


  