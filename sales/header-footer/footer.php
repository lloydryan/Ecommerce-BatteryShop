<style>
  .footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: white;
    padding: 60px 0 20px;
    margin-top: 80px;
    position: relative;
    overflow: hidden;
  }

  .footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
      radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
    z-index: 1;
  }

  .footer-content {
    position: relative;
    z-index: 2;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
  }

  .footer-main {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
  }

  .footer-brand {
    max-width: 300px;
  }

  .footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  .footer-logo-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .footer-logo-icon i {
    font-size: 18px;
    color: white;
  }

  .footer-logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
  }

  .footer-description {
    color: #b8c5d1;
    line-height: 1.6;
    margin-bottom: 25px;
    font-size: 0.95rem;
  }

  .footer-social {
    display: flex;
    gap: 12px;
  }

  .social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
  }

  .social-link:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
  }

  .footer-section h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: white;
    position: relative;
  }

  .footer-section h3::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 30px;
    height: 2px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 1px;
  }

  .footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer-links li {
    margin-bottom: 12px;
  }

  .footer-links a {
    color: #b8c5d1;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
  }

  .footer-links a:hover {
    color: #667eea;
    transform: translateX(5px);
  }

  .footer-links a i {
    margin-right: 8px;
    width: 16px;
    text-align: center;
  }

  .footer-contact {
    color: #b8c5d1;
    font-size: 0.9rem;
    line-height: 1.6;
  }

  .contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
  }

  .contact-item i {
    width: 20px;
    margin-right: 12px;
    color: #667eea;
  }

  .newsletter {
    background: rgba(255, 255, 255, 0.05);
    padding: 25px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .newsletter h3 {
    margin-bottom: 15px;
    font-size: 1.1rem;
  }

  .newsletter p {
    color: #b8c5d1;
    font-size: 0.85rem;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  .newsletter-form {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
  }

  .newsletter-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }

  .newsletter-input::placeholder {
    color: #b8c5d1;
  }

  .newsletter-input:focus {
    outline: none;
    border-color: #667eea;
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .newsletter-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
  }

  .newsletter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  }

  .footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
  }

  .footer-copyright {
    color: #8a9ba8;
    font-size: 0.85rem;
  }

  .footer-bottom-links {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
  }

  .footer-bottom-links a {
    color: #8a9ba8;
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.3s ease;
  }

  .footer-bottom-links a:hover {
    color: #667eea;
  }

  .back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
  }

  .back-to-top.visible {
    opacity: 1;
    visibility: visible;
  }

  .back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    color: white;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .footer-main {
      grid-template-columns: 1fr;
      gap: 30px;
    }

    .footer-brand {
      max-width: none;
      text-align: center;
    }

    .footer-social {
      justify-content: center;
    }

    .newsletter-form {
      flex-direction: column;
    }

    .footer-bottom {
      flex-direction: column;
      text-align: center;
    }

    .footer-bottom-links {
      justify-content: center;
    }
  }

  @media (max-width: 480px) {
    .footer {
      padding: 40px 0 20px;
    }

    .footer-content {
      padding: 0 15px;
    }

    .back-to-top {
      bottom: 20px;
      right: 20px;
      width: 45px;
      height: 45px;
    }
  }

  /* Animation for footer elements */
  .footer-section {
    animation: fadeInUp 0.6s ease-out;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<div class="footer">
  <div class="footer-content">
    <div class="footer-main">
      <!-- Brand Section -->
      <div class="footer-brand">
        <div class="footer-logo">
          <div class="footer-logo-icon">
            <i class="fas fa-battery-full"></i>
          </div>
          <div class="footer-logo-text">BatteryShop</div>
        </div>
        <p class="footer-description">
          Your trusted partner for premium battery solutions. We provide high-quality batteries 
          for all your devices with fast delivery and exceptional customer service.
        </p>
        <div class="footer-social">
          <a href="#" class="social-link" title="Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="social-link" title="Twitter">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="#" class="social-link" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="#" class="social-link" title="LinkedIn">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="#" class="social-link" title="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="footer-section">
        <h3>Quick Links</h3>
        <ul class="footer-links">
          <li><a href="home.php"><i class="fas fa-home"></i>Home</a></li>
          <li><a href="product.php"><i class="fas fa-shopping-bag"></i>Products</a></li>
          <li><a href="about.php"><i class="fas fa-info-circle"></i>About Us</a></li>
          <li><a href="orders.php"><i class="fas fa-shopping-cart"></i>My Orders</a></li>
          <li><a href="login.php"><i class="fas fa-sign-in-alt"></i>Login</a></li>
          <li><a href="signup.php"><i class="fas fa-user-plus"></i>Sign Up</a></li>
        </ul>
      </div>

      <!-- Categories -->
      <div class="footer-section">
        <h3>Categories</h3>
        <ul class="footer-links">
          <li><a href="#"><i class="fas fa-mobile-alt"></i>Phone Batteries</a></li>
          <li><a href="#"><i class="fas fa-laptop"></i>Laptop Batteries</a></li>
          <li><a href="#"><i class="fas fa-car"></i>Car Batteries</a></li>
          <li><a href="#"><i class="fas fa-battery-half"></i>Rechargeable</a></li>
          <li><a href="#"><i class="fas fa-tools"></i>Industrial</a></li>
          <li><a href="#"><i class="fas fa-gamepad"></i>Gaming</a></li>
        </ul>
      </div>

      <!-- Contact & Newsletter -->
      <div class="footer-section">
        <h3>Contact Info</h3>
        <div class="footer-contact">
          <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>123 Battery Street, Power City, PC 12345</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+1 (555) 123-4567</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>info@batteryshop.com</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-clock"></i>
            <span>Mon-Fri: 9AM-6PM</span>
          </div>
        </div>

        <div class="newsletter">
          <h3>Newsletter</h3>
          <p>Subscribe to get updates on new products and exclusive offers!</p>
          <form class="newsletter-form" action="#" method="POST">
            <input type="email" class="newsletter-input" placeholder="Enter your email" required>
            <button type="submit" class="newsletter-btn">Subscribe</button>
          </form>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-copyright">
        <p>&copy; 2024 BatteryShop. All rights reserved. | Developed with ❤️ by Full-Stack Developer</p>
      </div>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Sitemap</a>
      </div>
    </div>
  </div>
</div>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTop">
  <i class="fas fa-chevron-up"></i>
</a>

<script>
  // Back to top functionality
  document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('backToTop');
    
    // Show/hide back to top button
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    });
    
    // Smooth scroll to top
    backToTop.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
    
    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
      newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('.newsletter-input').value;
        
        // Show success message
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            title: 'Subscribed!',
            text: 'Thank you for subscribing to our newsletter.',
            icon: 'success',
            confirmButtonText: 'OK'
          });
        } else {
          alert('Thank you for subscribing to our newsletter!');
        }
        
        // Clear form
        this.querySelector('.newsletter-input').value = '';
      });
    }
    
    // Add hover effects to footer links
    const footerLinks = document.querySelectorAll('.footer-links a');
    footerLinks.forEach(link => {
      link.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(5px)';
      });
      
      link.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
      });
    });
  });
</script>