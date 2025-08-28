<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Titanic Collection</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            color: #333;
        }
        .about-header {
            background: url('https://images.unsplash.com/photo-1585421514287-27b2c045efd7') center/cover no-repeat;
            height: 300px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .about-header h1 {
            background-color: rgba(0,0,0,0.5);
            padding: 20px 40px;
            border-radius: 10px;
            font-size: 40px;
        }
        .about-content {
            padding: 40px 20px;
            max-width: 900px;
            margin: auto;
            line-height: 1.8;
        }
        .about-section {
            margin-bottom: 40px;
        }
        .about-section h2 {
            color: #FF7F50;
            margin-bottom: 15px;
        }
        .about-section p {
            font-size: 16px;
        }
        .values-list {
            list-style: none;
            padding: 0;
        }
        .values-list li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        .values-list li::before {
            content: "✔";
            position: absolute;
            left: 0;
            color: #40E0D0;
        }
        .contact-container {
            display: flex;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .contact-info {
            flex: 1;
            min-width: 300px;
        }
        .contact-info .info-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-info .info-item i {
            color: #FF7F50;
            width: 20px;
        }
        .map-container {
            flex: 1;
            min-width: 300px;
            min-height: 300px;
        }
        .map-container iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }
            .map-container {
                order: 2;
            }
            .contact-info {
                order: 1;
            }
        }
        footer {
            background-color: #222;
            color: #ddd;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
        }
        footer a {
            color: #ddd;
            margin: 0 10px;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>



<div class="about-content">
    <!-- Contact Section moved to top -->
    <div class="about-section">
        <h2>Contact Us & Location</h2>
        <p>
            Got questions, suggestions, or feedback? Reach out to us anytime. Visit our store or get in touch through any of the channels below.
        </p>
        
        <div class="contact-container">
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email:</strong><br>
                        titanich2024@gmail.com
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Phone:</strong><br>
                        +254 741421583
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Location:</strong><br>
                        Dubai Merchant Mall, Near RNG Plaza, Nairobi, Kenya<br>
                        <small>Visit us for an in-person shopping experience, Shop C67</small>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Business Hours:</strong><br>
                        Monday - Saturday: 9:00 AM - 8:00 PM<br>
                        Sunday: 10:00 AM - 6:00 PM
                    </div>
                </div>
            </div>
            
            <div class="map-container">
                <!-- Accurate Google Map embed for Dubai Merchant Mall near RNG Plaza -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.850314679242!2d36.8174969!3d-1.2839987999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f10d66adf64a7%3A0x2bb8c2e5b29a8020!2sDubai%20Merchant%20Mall%2C%20Kipande%20Road%20Near%20RNG%20Plaza%2C%20Nairobi!5e0!3m2!1sen!2ske!4v1691239999999!5m2!1sen!2ske"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Titanic Collection Location - Dubai Merchant Mall, Near RNG Plaza Nairobi">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Rest of About Content -->
    <div class="about-section">
        <h2>Our Story</h2>
        <p>
            Founded with a passion for bold expression and urban culture, Titanic Collection was created to revolutionize sneaker shopping in Kenya and beyond. From limited editions to everyday classics, we bring the most sought-after kicks right to your doorstep.
        </p>
    </div>

    <div class="about-section">
        <h2>What We Offer</h2>
        <p>
            Titanic Collection is your go-to source for premium sneakers, stylish apparel, and accessories. Whether you're a streetwear enthusiast, a collector, or someone who just loves great shoes, we've got something for you.
        </p>
    </div>

    <div class="about-section">
        <h2>Our Core Values</h2>
        <ul class="values-list">
            <li>Authenticity – 100% genuine products, always.</li>
            <li>Customer Satisfaction – Your happiness fuels us.</li>
            <li>Innovation – We stay ahead of the trends.</li>
            <li>Accessibility – Style should be for everyone.</li>
        </ul>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; <?php echo date('Y'); ?> Titanic Collection. All rights reserved.</p>
    <p>
        <a href="contact.php">Contact</a> |
        <a href="#">Privacy Policy</a> |
        <a href="#">Refund Policy</a> |
        <a href="#">Terms of Service</a> |
        <a href="#">Shipping</a>
    </p>
</footer>

</body>
</html>
