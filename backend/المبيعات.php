<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all sales
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to retrieve all sales
        $stmt = $pdo->prepare('SELECT * FROM المبيعات');
        $stmt->execute();
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return sales data in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($sales);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    
} 
// Handle GET request to retrieve a single sale by ID
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($input['id'])) {
    try {
        // Prepare SQL query to retrieve a single sale by ID
        $stmt = $pdo->prepare('SELECT * FROM المبيعات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return sale data in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($sale);
        
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(array('error' => 'Sale not found'));
    }
    
} 
// Handle POST request to create a new sale
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input data
        if (!isset($input['product_name']) || !isset($input['quantity']) || !isset($input['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Prepare SQL query to create a new sale
        $stmt = $pdo->prepare('INSERT INTO المبيعات (product_name, quantity, price) VALUES (:product_name, :quantity, :price)');
        $stmt->bindParam(':product_name', $input['product_name']);
        $stmt->bindParam(':quantity', $input['quantity']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();
        
        // Return sale ID in JSON format
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Database error'));
    }
    
} 
// Handle PUT request to update an existing sale
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Validate and sanitize input data
        if (!isset($input['id']) || !isset($input['product_name']) || !isset($input['quantity']) || !isset($input['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Check if user is admin to perform update operation
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }
        
        // Prepare SQL query to update an existing sale
        $stmt = $pdo->prepare('UPDATE المبيعات SET product_name = :product_name, quantity = :quantity, price = :price WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':product_name', $input['product_name']);
        $stmt->bindParam(':quantity', $input['quantity']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();
        
        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Sale updated successfully'));
        
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(array('error' => 'Sale not found'));
    }
    
} 
// Handle DELETE request to delete a sale
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($input['id'])) {
    try {
        // Check if user is admin to perform delete operation
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }
        
        // Prepare SQL query to delete a sale
        $stmt = $pdo->prepare('DELETE FROM المبيعات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Sale deleted successfully'));
        
    } catch (PDOException $e) {
        http_response_code(404);
        echo json_encode(array('error' => 'Sale not found'));
    }
    
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}