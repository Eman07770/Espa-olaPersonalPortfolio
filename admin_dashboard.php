<?php
session_start();
require_once 'database.php';


if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin_login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Fetch all contact messages
$query = "SELECT * FROM contact_messages ORDER BY date_sent DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Eman Española</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-container {
            min-height: 100vh;
            background: #1D2331;
            padding: 100px 20px 20px;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #8EFF01;
        }
        
        .admin-title {
            color: #8EFF01;
            margin: 0;
        }
        
        .admin-actions {
            display: flex;
            gap: 1rem;
        }
        
        .messages-count {
            background: #8EFF01;
            color: #1D2331;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .messages-table {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .table-header {
            display: grid;
            grid-template-columns: 1fr 2fr 3fr 2fr;
            gap: 1rem;
            padding: 1.5rem;
            background: rgba(143, 255, 1, 0.1);
            border-bottom: 1px solid rgba(143, 255, 1, 0.3);
            font-weight: bold;
            color: #8EFF01;
        }
        
        .message-row {
            display: grid;
            grid-template-columns: 1fr 2fr 3fr 2fr;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease;
        }
        
        .message-row:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .message-row:last-child {
            border-bottom: none;
        }
        
        .message-name {
            color: #FFFFFF;
            font-weight: 500;
        }
        
        .message-email {
            color: #8EFF01;
        }
        
        .message-text {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.4;
        }
        
        .message-date {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        .no-messages {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.6);
        }
        
        @media (max-width: 768px) {
            .table-header,
            .message-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .admin-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <h2>Eman Española's</h2>
            <span>Admin Panel</span>
        </div>
        <ul class="nav-menu">
            <li><a href="index.php#home" class="nav-link">Home</a></li>
            <li><a href="index.php#about" class="nav-link">About</a></li>
            
            
            <li><a href="contact.php" class="nav-link">Contact</a></li>
            <li><a href="admin_dashboard.php" class="nav-link">Admin Dashboard</a></li>
        </ul>
    </div>
</nav>

    <div class="admin-container">
        <div class="container">
            <div class="admin-header">
                <h1 class="admin-title">Contact Messages</h1>
                <div class="admin-actions">
                    <div class="messages-count">
                        <?php echo count($messages); ?> Messages
                    </div>
                    <a href="?logout=true" class="btn btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <div class="messages-table">
                <?php if (count($messages) > 0): ?>
                    <div class="table-header">
                        <div>Name</div>
                        <div>Email</div>
                        <div>Message</div>
                        <div>Date Sent</div>
                    </div>
                    
                    <?php foreach ($messages as $message): ?>
                        <div class="message-row">
                            <div class="message-name"><?php echo htmlspecialchars($message['name']); ?></div>
                            <div class="message-email"><?php echo htmlspecialchars($message['email']); ?></div>
                            <div class="message-text"><?php echo htmlspecialchars($message['message']); ?></div>
                            <div class="message-date"><?php echo date('M j, Y g:i A', strtotime($message['date_sent'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-messages">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <h3>No messages yet</h3>
                        <p>Contact messages will appear here once users start submitting them.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>