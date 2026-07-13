<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Define function for CRUD operations
function crudOperation($method, $table, $data = null, $id = null) {
    global $pdo, $userRole, $userID;

    // Check if user is logged in
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        return;
    }

    // Prepare SQL query
    $query = $pdo->prepare($method === 'GET' ? "SELECT * FROM $table WHERE 1=1" : "INSERT INTO $table SET ");

    // Add conditions to query based on method
    if ($method === 'GET') {
        // Handle GET request
        if ($id) {
            $query->bindParam(':id', $id);
            $query->execute();
            $result = $query->fetch();
            if ($result) {
                http_response_code(200);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
            }
        } else {
            // Handle GET all request
            $query->execute();
            $result = $query->fetchAll();
            http_response_code(200);
            echo json_encode($result);
        }
    } elseif ($method === 'POST') {
        // Handle POST request
        $query->bindParam(':name', $data['name']);
        $query->bindParam(':description', $data['description']);
        $query->bindParam(':created_by', $userID);
        $query->execute();
        http_response_code(201);
        echo json_encode(['message' => 'Created successfully']);
    } elseif ($method === 'PUT') {
        // Handle PUT request
        if ($userRole !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        $query->bindParam(':id', $id);
        $query->bindParam(':name', $data['name']);
        $query->bindParam(':description', $data['description']);
        $query->bindParam(':updated_by', $userID);
        $query->execute();
        http_response_code(200);
        echo json_encode(['message' => 'Updated successfully']);
    } elseif ($method === 'DELETE') {
        // Handle DELETE request
        if ($userRole !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        $query->bindParam(':id', $id);
        $query->execute();
        http_response_code(200);
        echo json_encode(['message' => 'Deleted successfully']);
    }
}

// Handle requests
if (isset($_GET['id'])) {
    crudOperation('GET', 'التسويق', null, $_GET['id']);
} elseif (isset($_POST['name'])) {
    crudOperation('POST', 'التسويق', $_POST);
} elseif (isset($_GET['id']) && isset($_POST['name'])) {
    crudOperation('PUT', 'التسويق', $_POST, $_GET['id']);
} elseif (isset($_GET['id'])) {
    crudOperation('DELETE', 'التسويق', null, $_GET['id']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}



// Define routes
$routes = [
    '/التسويق' => 'GET',
    '/التسويق' => 'POST',
    '/التسويق/{id}' => 'GET',
    '/التسويق/{id}' => 'PUT',
    '/التسويق/{id}' => 'DELETE'
];

// Define allowed methods
$allowedMethods = ['GET', 'POST', 'PUT', 'DELETE'];

// Define route handlers
$routeHandlers = [
    '/التسويق' => 'crudOperation',
    '/التسويق/{id}' => 'crudOperation'
];

// Define route parameters
$routeParams = [
    '/التسويق/{id}' => ['id' => 'id']
];

// Define route validation rules
$routeValidationRules = [
    '/التسويق' => [
        'name' => 'required|string',
        'description' => 'required|string'
    ],
    '/التسويق/{id}' => [
        'name' => 'required|string',
        'description' => 'required|string'
    ]
];

// Define route sanitization rules
$routeSanitizationRules = [
    '/التسويق' => [
        'name' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string'
    ],
    '/التسويق/{id}' => [
        'name' => 'trim|sanitize_string',
        'description' => 'trim|sanitize_string'
    ]
];

// Define route output processing rules
$routeOutputProcessingRules = [
    '/التسويق' => [
        'message' => 'success'
    ],
    '/التسويق/{id}' => [
        'message' => 'success'
    ]
];