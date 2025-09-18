    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");
    const toggleIcon = document.getElementById("toggleIcon");

    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("d-none");

        if (sidebar.classList.contains("d-none")) {
            toggleIcon.classList.remove("bi-x");
            toggleIcon.classList.add("bi-list");
        } else {
            toggleIcon.classList.remove("bi-list");
            toggleIcon.classList.add("bi-x");
        }
    });
    function setActiveLink() {
    const links = document.querySelectorAll('.navigation ul li a');
    links.forEach(link => {
      if (link.href === window.location.href) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }
    });
}

// Call the function when the page is loaded
document.addEventListener('DOMContentLoaded', setActiveLink);