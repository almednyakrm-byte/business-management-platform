<?php
session_start();

// Include database connection file
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return JSON response with user data
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user_data = mysqli_fetch_assoc($result);
    echo json_encode(array('status' => 'logged_in', 'user_data' => $user_data));
    exit;
}

// Handle login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare SQL query for login
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user_data = mysqli_fetch_assoc($result);

        // Check if user exists
        if ($user_data) {
            // Verify password
            if (password_verify($password, $user_data['password'])) {
                // If password is correct, log user in
                $_SESSION['user_id'] = $user_data['id'];
                echo json_encode(array('status' => 'logged_in'));
            } else {
                // If password is incorrect, return error message
                echo json_encode(array('status' => 'error', 'message' => 'Invalid password'));
            }
        } else {
            // If user does not exist, return error message
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
        }
    } else {
        // If username or password is missing, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Missing username or password'));
    }
}

// Handle register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare SQL query for registration
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, password_hash($password, PASSWORD_DEFAULT));
        mysqli_stmt_execute($stmt);

        // Check if registration was successful
        if (mysqli_stmt_affected_rows($stmt) == 1) {
            // If registration was successful, return success message
            echo json_encode(array('status' => 'registered'));
        } else {
            // If registration failed, return error message
            echo json_encode(array('status' => 'error', 'message' => 'Registration failed'));
        }
    } else {
        // If username, email, or password is missing, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Missing username, email, or password'));
    }
}

// Handle logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy session
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
}

// Handle GET request to check session status
if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        // If user is logged in, return JSON response with user data
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = '$user_id'";
        $result = mysqli_query($conn, $query);
        $user_data = mysqli_fetch_assoc($result);
        echo json_encode(array('status' => 'logged_in', 'user_data' => $user_data));
    } else {
        // If user is not logged in, return JSON response with logged out status
        echo json_encode(array('status' => 'logged_out'));
    }
}