<?php
session_start();
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

$name = '';
$showContactForm = false;
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step1'])) {
       
        $name = trim($_POST['name']);
        if (!empty($name)) {
            $_SESSION['visitor_name'] = $name;
            $showContactForm = true;
        }
    } elseif (isset($_POST['step2'])) {
        
        if (isset($_SESSION['visitor_name'])) {
            $name = $_SESSION['visitor_name'];
            $email = trim($_POST['email']);
            $message = trim($_POST['message']);
            
            
            if (!empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $query = "INSERT INTO contact_messages (name, email, message) VALUES (:name, :email, :message)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(":name", $name);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":message", $message);
                    
                    if ($stmt->execute()) {
                        $successMessage = "Thank you for your message, $name! I'll get back to you soon.";
                        unset($_SESSION['visitor_name']);
                        $showContactForm = false;
                    }
                } catch(PDOException $exception) {
                    $successMessage = "Sorry, there was an error sending your message. Please try again.";
                }
            } else {
                $successMessage = "Please provide a valid email and message.";
                $showContactForm = true;
            }
        }
    }
}


if (isset($_SESSION['visitor_name']) && !$showContactForm) {
    $name = $_SESSION['visitor_name'];
    $showContactForm = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Eman Española</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <h2>Eman Española's</h2>
            <span>Personal Portfolio</span>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php#home" class="nav-link">Home</a></li>
            <li><a href="index.php#about" class="nav-link">About</a></li>
            
            <li><a href="contact.php" class="nav-link">Contact</a></li>
            <li><a href="admin_login.php" class="nav-link">Log-in</a></li>
        </ul>
    </div>
</nav>

    <!-- Contact Section -->
    <section class="contact-page">
        <div class="container">
            <div class="contact-header">
                <h1 class="section-title">Get In Touch</h1>
                <p class="contact-subtitle">Have a project in mind? Let's work together to bring your ideas to life.</p>
            </div>
            
            <?php if (!empty($successMessage)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <div class="contact-content">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>eman.espanola@example.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Phone</h4>
                            <p>+63 912 345 6789</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Location</h4>
                            <p>Caloocan City, Philippines</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <?php if (!$showContactForm && empty($successMessage)): ?>
                        
                        <form id="contactForm" class="contact-form" method="POST">
                            <input type="hidden" name="step1" value="1">
                            <div class="form-group">
                                <label for="name">What's your name?</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" required 
                                       value="<?php echo htmlspecialchars($name); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </form>
                    <?php elseif ($showContactForm): ?>
                        
                        <div class="welcome-message">
                            <h3>Welcome, <?php echo htmlspecialchars($name); ?>!</h3>
                            <p>Thank you for reaching out. Please fill out the form below and I'll get back to you soon.</p>
                        </div>
                        <form id="contactForm" class="contact-form" method="POST">
                            <input type="hidden" name="step2" value="1">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Your Message</label>
                                <textarea id="message" name="message" rows="6" placeholder="Tell me about your project..." required></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3>Eman Eliseo E. Española</h3>
                    <p>An IT Student</p>
                </div>
                <div class="social-links">
                    <a href="https://www.facebook.com/emanespanola00" target="_blank" class="social-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/sonofthors77/" target="_blank" class="social-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Eman Española's Personal Portfolio. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>