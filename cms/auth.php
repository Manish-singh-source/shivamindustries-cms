<?php
/**
 * Authentication Helper Functions
 * Handles user session, login, registration
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Users CSV file path
define('USERS_FILE', __DIR__ . '/../users.csv');

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require authentication - redirect to login if not logged in
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Get current logged in user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user'
    ];
}

/**
 * Initialize users CSV file with headers if not exists
 */
function initUsersFile() {
    if (!file_exists(USERS_FILE)) {
        $file = fopen(USERS_FILE, 'w');
        if ($file) {
            fputcsv($file, ['id', 'name', 'email', 'password', 'role', 'created_at', 'status']);
            fclose($file);
        }
    }
}

/**
 * Get all users from CSV
 */
function getAllUsers() {
    initUsersFile();
    $users = [];
    
    if (($file = fopen(USERS_FILE, 'r')) !== FALSE) {
        $header = fgetcsv($file); // Skip header
        while (($row = fgetcsv($file)) !== FALSE) {
            if (count($row) >= 7) {
                $users[] = [
                    'id' => $row[0],
                    'name' => $row[1],
                    'email' => $row[2],
                    'password' => $row[3],
                    'role' => $row[4],
                    'created_at' => $row[5],
                    'status' => $row[6]
                ];
            }
        }
        fclose($file);
    }
    
    return $users;
}

/**
 * Find user by email
 */
function findUserByEmail($email) {
    $users = getAllUsers();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

/**
 * Register new user
 */
function registerUser($name, $email, $password, $role = 'user') {
    initUsersFile();
    
    // Check if email already exists
    if (findUserByEmail($email)) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Generate new ID
    $users = getAllUsers();
    $newId = count($users) + 1;
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Add new user
    $file = fopen(USERS_FILE, 'a');
    if ($file) {
        fputcsv($file, [
            $newId,
            $name,
            $email,
            $hashedPassword,
            $role,
            date('Y-m-d H:i:s'),
            '1' // Active status
        ]);
        fclose($file);
        return ['success' => true, 'message' => 'Registration successful'];
    }
    
    return ['success' => false, 'message' => 'Failed to register user'];
}

/**
 * Login user
 */
function loginUser($email, $password) {
    $user = findUserByEmail($email);
    
    if (!$user) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    if ($user['status'] !== '1') {
        return ['success' => false, 'message' => 'Your account is inactive'];
    }
    
    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    return ['success' => true, 'message' => 'Login successful'];
}

/**
 * Logout user
 */
function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit;
}

