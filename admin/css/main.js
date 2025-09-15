    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleIcon = document.getElementById('toggleIcon');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        content.classList.toggle('active');
        
        if (sidebar.classList.contains('active')) {
            toggleIcon.classList.remove('bi-x');
            toggleIcon.classList.add('bi-list');
        } else {
            toggleIcon.classList.remove('bi-list');
            toggleIcon.classList.add('bi-x');
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