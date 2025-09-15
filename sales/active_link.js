function setActiveLink() {
  const links = document.querySelectorAll('.navbar-nav .nav-link');
  links.forEach(link => {
    if (link.href === window.location.href) {
      link.parentElement.classList.add('active');
    } else {
      link.parentElement.classList.remove('active');
    }
  });
}

// Call the function when the page is loaded
setActiveLink();

// Call the function when a new link is clicked
document.addEventListener('click', setActiveLink);
