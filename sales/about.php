<?php
include_once('connections/connection.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - BatteryShop</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: -1;
        }

        .hero-section {
            padding: 120px 0 80px;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
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
            font-weight: 400;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .about-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .about-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            margin-bottom: 40px;
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .about-content {
            padding: 60px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #4b5563;
            margin-bottom: 30px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }

        .feature-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .feature-icon i {
            font-size: 24px;
            color: white;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .feature-description {
            color: #6b7280;
            line-height: 1.6;
        }

        .creator-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px;
            text-align: center;
            position: relative;
        }

        .creator-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('images/repair.jpg') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        .creator-content {
            position: relative;
            z-index: 2;
        }

        .creator-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .creator-avatar i {
            font-size: 48px;
            color: white;
        }

        .creator-name {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .creator-title {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .creator-description {
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.95;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .social-link {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }

        .stat-card {
            text-align: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .cta-section {
            text-align: center;
            padding: 60px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .cta-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 16px;
        }

        .cta-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 30px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid #667eea;
            padding: 12px 30px;
            border-radius: 12px;
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .about-content {
                padding: 40px 30px;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .creator-section {
                padding: 40px 30px;
            }
            
            .creator-name {
                font-size: 1.8rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .cta-section {
                padding: 40px 30px;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 100px 0 60px;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .about-content {
                padding: 30px 20px;
            }
            
            .creator-section {
                padding: 30px 20px;
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
        <div class="hero-content">
            <h1 class="hero-title" data-aos="fade-up">About BatteryShop</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Your trusted partner for premium battery solutions and exceptional service
            </p>
        </div>
    </section>

    <div class="about-container">
        <!-- About Us Section -->
        <div class="about-card" data-aos="fade-up">
            <div class="about-content">
                <h2 class="section-title">Our Story</h2>
                <p class="section-subtitle">Delivering power solutions since day one</p>
                
                <p class="about-text">
                    BatteryShop was founded with a simple mission: to provide high-quality battery solutions 
                    that power your devices and your life. We understand that reliable power is essential 
                    in today's connected world, and we're committed to delivering products that meet the 
                    highest standards of quality and performance.
                </p>
                
                <p class="about-text">
                    Our journey began with a vision to make premium battery technology accessible to everyone. 
                    From smartphones to laptops, from automotive to industrial applications, we've built 
                    a comprehensive inventory that serves diverse needs across various industries.
                </p>
                
                <p class="about-text">
                    Today, we're proud to serve thousands of customers who trust us for their power needs. 
                    Our commitment to excellence, combined with our customer-first approach, has made us 
                    a leading name in the battery industry.
                </p>
            </div>
        </div>

        <!-- Features Section -->
        <div class="about-card" data-aos="fade-up" data-aos-delay="200">
            <div class="about-content">
                <h2 class="section-title">Why Choose BatteryShop?</h2>
                <p class="section-subtitle">We're committed to delivering excellence in every aspect</p>
                
                <div class="features-grid">
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="100">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3 class="feature-title">Premium Quality</h3>
                        <p class="feature-description">
                            We source only the highest quality batteries from trusted manufacturers, 
                            ensuring reliability and longevity for all your devices.
                        </p>
                    </div>
                    
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="feature-title">Fast Delivery</h3>
                        <p class="feature-description">
                            Get your batteries delivered quickly with our efficient shipping network. 
                            Most orders arrive within 2-3 business days.
                        </p>
                    </div>
                    
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="300">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Expert Support</h3>
                        <p class="feature-description">
                            Our knowledgeable team is here to help you find the perfect battery solution 
                            for your specific needs and requirements.
                        </p>
                    </div>
                    
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="400">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Warranty Protection</h3>
                        <p class="feature-description">
                            All our products come with comprehensive warranty coverage, giving you 
                            peace of mind with every purchase.
                        </p>
                    </div>
                    
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="500">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="feature-title">Competitive Pricing</h3>
                        <p class="feature-description">
                            We offer the best value for money with competitive prices that don't 
                            compromise on quality or service.
                        </p>
                    </div>
                    
                    <div class="feature-card" data-aos="zoom-in" data-aos-delay="600">
                        <div class="feature-icon">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <h3 class="feature-title">Eco-Friendly</h3>
                        <p class="feature-description">
                            We're committed to environmental responsibility with proper battery 
                            recycling programs and sustainable practices.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Creator Section -->
        <div class="about-card" data-aos="fade-up" data-aos-delay="400">
            <div class="creator-section">
                <div class="creator-content">
                    <div class="creator-avatar" data-aos="zoom-in">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    
                    <h2 class="creator-name" data-aos="fade-up" data-aos-delay="200">About the Creator</h2>
                    <p class="creator-title" data-aos="fade-up" data-aos-delay="300">Full-Stack Developer & E-commerce Specialist</p>
                    
                    <p class="creator-description" data-aos="fade-up" data-aos-delay="400">
                        Hi there! I'm the developer behind BatteryShop, a passionate full-stack developer 
                        with expertise in modern web technologies. I created this e-commerce platform to 
                        demonstrate my skills in PHP, MySQL, JavaScript, and responsive web design.
                    </p>
                    
                    <p class="creator-description" data-aos="fade-up" data-aos-delay="500">
                        This project showcases a complete e-commerce solution with user authentication, 
                        product management, inventory tracking, and a modern, responsive design. I believe 
                        in creating digital experiences that are not only functional but also beautiful 
                        and user-friendly.
                    </p>
                    
                    <div class="stats-grid" data-aos="fade-up" data-aos-delay="600">
                        <div class="stat-card">
                            <div class="stat-number">3+</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Projects Completed</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Client Satisfaction</div>
                        </div>
                    </div>
                    
                    <div class="social-links" data-aos="fade-up" data-aos-delay="700">
                        <a href="#" class="social-link">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="about-card" data-aos="fade-up" data-aos-delay="600">
            <div class="cta-section">
                <h2 class="cta-title">Ready to Power Up?</h2>
                <p class="cta-subtitle">Join thousands of satisfied customers and discover the perfect battery solution for your needs</p>
                
                <div class="cta-buttons">
                    <a href="product.php" class="btn-primary-custom">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Shop Now
                    </a>
                    <a href="home.php" class="btn-outline-custom">
                        <i class="fas fa-home me-2"></i>
                        Go Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php include_once('header-footer/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Add smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>
