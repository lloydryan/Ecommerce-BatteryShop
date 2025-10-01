<?php
include_once('connections/connection.php');
session_start();

if (isset($_GET['logout'])) {
  $_SESSION = array();
  session_destroy();
  header("Location: home.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BatteryShop - Premium Battery Solutions</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="active_link.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(0, 9, 87, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(52, 76, 183, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .hero-section {
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/theOne.png') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 24px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .cta-primary {
            background: linear-gradient(135deg, #FFEB00 0%, #FFEB00 100%);
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            color: #000957;
            font-weight: 700;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 235, 0, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 235, 0, 0.4);
            color: #000957;
        }

        .cta-secondary {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 14px 30px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .cta-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            color: white;
        }

        .features-section {
            padding: 100px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .feature-card {
            text-align: center;
            padding: 40px 30px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #000957, #344CB7, #577BC1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #000957, #344CB7, #577BC1);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 25px rgba(0, 9, 87, 0.3);
        }

        .feature-icon i {
            font-size: 32px;
            color: white;
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
        }

        .feature-description {
            color: #6b7280;
            line-height: 1.6;
            font-size: 1rem;
        }

        .carousel-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 100px 0;
            position: relative;
        }

        .carousel-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .carousel-custom {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            background: white;
        }

        .carousel-item img {
            height: 400px;
            object-fit: cover;
            width: 100%;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: 60px;
            height: 60px;
            background: rgba(102, 126, 234, 0.9);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            transition: all 0.3s ease;
        }

        .carousel-control-prev {
            left: -30px;
        }

        .carousel-control-next {
            right: -30px;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(102, 126, 234, 1);
            transform: translateY(-50%) scale(1.1);
        }

        .stats-section {
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/theOne.png') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        .stats-content {
            position: relative;
            z-index: 2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-top: 60px;
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .cta-section {
            background: white;
            padding: 100px 0;
            text-align: center;
        }

        .cta-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .cta-subtitle {
            font-size: 1.2rem;
            color: #6b7280;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 9, 87, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 9, 87, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid #000957;
            padding: 14px 30px;
            border-radius: 12px;
            color: #000957;
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .btn-outline-custom:hover {
            background: #000957;
            color: white;
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-cta {
                flex-direction: column;
                align-items: stretch;
            }
            
            .cta-primary,
            .cta-secondary {
                justify-content: center;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .carousel-control-prev {
                left: 10px;
            }
            
            .carousel-control-next {
                right: 10px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .features-section,
            .carousel-section,
            .stats-section,
            .cta-section {
                padding: 60px 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
  </head>
<body>
    <?php include_once('header-footer/header.php') ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content" data-aos="fade-right">
                        <h1 class="hero-title">Power Up Your Day with Premium Battery Solutions</h1>
                        <p class="hero-subtitle">
                            Discover our wide range of high-quality batteries for all your devices. 
                            From smartphones to laptops, we have the perfect power solution for you.
                        </p>
                        <div class="hero-cta">
                            <a href="product.php" class="cta-primary">
                                <i class="fas fa-shopping-cart"></i>
                                Shop Now
                            </a>
                            <a href="about.php" class="cta-secondary">
                                <i class="fas fa-info-circle"></i>
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-content" data-aos="fade-left">
                        <div class="carousel-container">
                            <div id="heroCarousel" class="carousel slide carousel-custom" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="assets/1.png" class="d-block w-100" alt="Premium Batteries">
                    </div>
                    <div class="carousel-item">
                                        <img src="assets/2.png" class="d-block w-100" alt="Battery Collection">
                    </div>
                    <div class="carousel-item">
                                        <img src="assets/3.png" class="d-block w-100" alt="Power Solutions">
                    </div>
                    </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title" data-aos="fade-up">Why Choose BatteryShop?</h2>
                    <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                        We're committed to providing the best battery solutions with exceptional service
                    </p>
                </div>
            </div>
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="feature-title">Premium Quality</h3>
                    <p class="feature-description">
                        We source only the highest quality batteries from trusted manufacturers, 
                        ensuring reliability and longevity for all your devices.
                    </p>
    </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3 class="feature-title">Fast Delivery</h3>
                    <p class="feature-description">
                        Get your batteries delivered quickly with our efficient shipping network. 
                        Most orders arrive within 2-3 business days.
                    </p>
             </div>
          
                <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">Expert Support</h3>
                    <p class="feature-description">
                        Our knowledgeable team is here to help you find the perfect battery solution 
                        for your specific needs and requirements.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-content">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="section-title" data-aos="fade-up">Trusted by Thousands</h2>
                        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
                            Join our growing community of satisfied customers
                        </p>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-number">50,000+</div>
                        <div class="stat-label">Batteries Sold</div>
                    </div>
                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-number">99%</div>
                        <div class="stat-label">Customer Satisfaction</div>
                    </div>
                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Customer Support</div>
    </div>
  </div>
</div>
</div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title" data-aos="fade-up">Ready to Power Up?</h2>
                <p class="cta-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Explore our premium battery collection and find the perfect power solution for your needs. 
                    Fast delivery, expert support, and guaranteed quality.
                </p>
                <div class="cta-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="product.php" class="btn-primary-custom">
                        <i class="fas fa-shopping-bag"></i>
                        Browse Products
                    </a>
                    <a href="about.php" class="btn-outline-custom">
                        <i class="fas fa-info-circle"></i>
                        About Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include_once('header-footer/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add hover effects to feature cards
        document.addEventListener('DOMContentLoaded', function() {
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add hover effects to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add counter animation for stats
            const statNumbers = document.querySelectorAll('.stat-number');
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalNumber = target.textContent;
                        
                        // Extract number from text (remove +, %, etc.)
                        const number = parseInt(finalNumber.replace(/[^\d]/g, ''));
                        if (number) {
                            animateCounter(target, 0, number, 2000, finalNumber);
                        }
                        
                        observer.unobserve(target);
                    }
                });
            }, observerOptions);

            statNumbers.forEach(number => {
                observer.observe(number);
            });

            function animateCounter(element, start, end, duration, originalText) {
                const startTime = performance.now();
                const isPercentage = originalText.includes('%');
                const hasPlus = originalText.includes('+');
                const hasSlash = originalText.includes('/');

                function updateCounter(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function for smooth animation
                    const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                    const current = Math.floor(start + (end - start) * easeOutQuart);
                    
                    let displayText = current.toString();
                    if (isPercentage) displayText += '%';
                    if (hasPlus) displayText += '+';
                    if (hasSlash) displayText = originalText; // For 24/7, keep original
                    
                    element.textContent = displayText;
                    
                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                }
                
                requestAnimationFrame(updateCounter);
            }
        });

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        window.onscroll = () => {
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
    </script>
</body>
</html>
