<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bidding Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero-section {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://via.placeholder.com/1920x800');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 150px 20px;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin: 20px 0;
        }
        .features-section .feature-card {
            text-align: center;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .features-section .feature-card:hover {
            transform: translateY(-10px);
        }
        .testimonials-section {
            background-color: #f9f9f9;
            padding: 60px 20px;
        }
        .testimonials-section .testimonial {
            margin: 20px 0;
            font-style: italic;
        }
        .cta-section {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 50px 20px;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to Our Online Bidding Platform</h1>
            <p>Discover, bid, and win your favorite items in real-time auctions!</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Join Now</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-5">
        <div class="container text-center">
            <h2>What Makes Us Special?</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="feature-card bg-light">
                        <h5>Real-Time Auctions</h5>
                        <p>Participate in live auctions and experience the thrill of bidding.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-light">
                        <h5>Verified Sellers</h5>
                        <p>Buy with confidence from trusted and verified sellers.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-light">
                        <h5>Secure Payments</h5>
                        <p>Your transactions are safe and protected by the latest technology.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container text-center">
            <h2>What Our Users Say</h2>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="testimonial">
                        <p>"This platform is amazing! I found unique products at great prices."</p>
                        <strong>- Sarah M.</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="testimonial">
                        <p>"The bidding process was seamless and exciting. Highly recommend!"</p>
                        <strong>- James K.</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Bidding?</h2>
            <p>Create your account today and join thousands of satisfied users.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Sign Up Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-3 bg-dark text-white text-center">
        <p>&copy; {{ date('Y') }} Online Bidding Platform. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
