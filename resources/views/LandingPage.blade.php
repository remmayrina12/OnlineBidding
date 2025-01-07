<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bidding Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .hero-section {
            background: url('assets/panibagong logo eyyy.png') no-repeat center center/cover;
            height: 53vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: black;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .hero-section .container {
            position: relative;
            z-index: 2;
        }

        .hero-section h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.7);
        }

        .hero-section .btn {
            padding: 10px 25px;
            font-size: 1.2rem;
            border-radius: 25px;
        }

        .cta-section {
            text-align: center;
            padding: 50px 20px;
            background-image: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .cta-section .btn {
            padding: 10px 25px;
            font-size: 1.2rem;
            border-radius: 25px;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1.2rem;
            }

            .cta-section h2 {
                font-size: 2rem;
            }

            .cta-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to Our Online Bidding Platform</h1>
            <p>Discover, bid, and win your favorite items in auctions!</p>
            <a href="#" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#authModal">Join Now</a>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg">Sign Up Now</a>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Bidding?</h2>
            <p>Create your account today and join thousands of satisfied users.</p>
            <a href="{{ route('register') }}" class="btn btn-light">Get Started</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-3 bg-dark text-white text-center">
        <p>&copy; {{ date('Y') }} Online Bidding Platform. All rights reserved.</p>
    </footer>

    <!-- Modal Structure -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="authModalLabel">Choose an Option</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
            <p>Welcome! Please choose an option to continue:</p>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg">Register</a>
            </div>
        </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
