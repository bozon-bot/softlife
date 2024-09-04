<?php
// Start the session
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data (if needed)
// For simplicity, we'll just use the username from the session
$username = $_SESSION['user'];

// Database credentials
$host = 'localhost'; // Your database host
$db = 'investments'; // Your database name
$user = 'Username'; // Your database username
$pass = 'Password'; // Your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Fetch user investments
$stmt = $pdo->prepare("SELECT * FROM investments WHERE username = :username");
$stmt->execute(['username' => $username]);
$investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Soft Life Investments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        #profile-container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div id="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <h2>Your Investments</h2>
        <table>
            <thead>
                <tr>
                    <th>Investment ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($investments)) : ?>
                    <tr>
                        <td colspan="3">No investments found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($investments as $investment) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($investment['id']); ?></td>
                            <td><?php echo htmlspecialchars($investment['amount']); ?></td>
                            <td><?php echo htmlspecialchars($investment['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>