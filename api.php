<?php
/**
 * Simple REST API for CRUD operations
 * Usage: api.php?action=create|read|update|delete&id=123
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'classes/User.php';

try {
    $user = new User();
    $action = $_GET['action'] ?? '';
    $response = ['success' => false, 'message' => '', 'data' => null];

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($action === 'read') {
                $id = $_GET['id'] ?? null;
                if ($id) {
                    $data = $user->read($id);
                    if ($data) {
                        $response = ['success' => true, 'data' => $data];
                    } else {
                        $response['message'] = 'User not found';
                        http_response_code(404);
                    }
                } else {
                    $data = $user->readAll();
                    $response = ['success' => true, 'data' => $data];
                }
            } elseif ($action === 'search') {
                $searchTerm = $_GET['q'] ?? '';
                if ($searchTerm) {
                    $data = $user->search($searchTerm);
                    $response = ['success' => true, 'data' => $data];
                } else {
                    $response['message'] = 'Search term required';
                    http_response_code(400);
                }
            } else {
                $response['message'] = 'Invalid action';
                http_response_code(400);
            }
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            
            if ($action === 'create') {
                if (!$input || !isset($input['name']) || !isset($input['email'])) {
                    $response['message'] = 'Name and email are required';
                    http_response_code(400);
                    break;
                }

                // Check if email exists
                if ($user->emailExists($input['email'])) {
                    $response['message'] = 'Email already exists';
                    http_response_code(409);
                    break;
                }

                $data = [
                    'name' => trim($input['name']),
                    'email' => trim($input['email']),
                    'phone' => trim($input['phone'] ?? ''),
                    'address' => trim($input['address'] ?? '')
                ];

                $result = $user->create($data);
                if ($result) {
                    $response = [
                        'success' => true,
                        'message' => 'User created successfully',
                        'data' => ['id' => $result]
                    ];
                    http_response_code(201);
                } else {
                    $response['message'] = 'Failed to create user';
                    http_response_code(500);
                }
            } else {
                $response['message'] = 'Invalid action';
                http_response_code(400);
            }
            break;

        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                $response['message'] = 'User ID required';
                http_response_code(400);
                break;
            }

            if (!$input || !isset($input['name']) || !isset($input['email'])) {
                $response['message'] = 'Name and email are required';
                http_response_code(400);
                break;
            }

            // Check if email exists (excluding current user)
            if ($user->emailExists($input['email'], $id)) {
                $response['message'] = 'Email already exists';
                http_response_code(409);
                break;
            }

            $data = [
                'name' => trim($input['name']),
                'email' => trim($input['email']),
                'phone' => trim($input['phone'] ?? ''),
                'address' => trim($input['address'] ?? '')
            ];

            $result = $user->update($id, $data);
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'User updated successfully'
                ];
            } else {
                $response['message'] = 'Failed to update user or user not found';
                http_response_code(404);
            }
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                $response['message'] = 'User ID required';
                http_response_code(400);
                break;
            }

            $result = $user->delete($id);
            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'User deleted successfully'
                ];
            } else {
                $response['message'] = 'Failed to delete user or user not found';
                http_response_code(404);
            }
            break;

        default:
            $response['message'] = 'Method not allowed';
            http_response_code(405);
            break;
    }

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ];
    http_response_code(500);
}

echo json_encode($response);
?>

<?php
/**
 * API Usage Examples:
 * 
 * GET /api.php?action=read              - Get all users
 * GET /api.php?action=read&id=1         - Get user by ID
 * GET /api.php?action=search&q=john     - Search users
 * 
 * POST /api.php?action=create           - Create user (JSON body required)
 * PUT /api.php?action=update&id=1       - Update user (JSON body required)
 * DELETE /api.php?action=delete&id=1    - Delete user
 * 
 * JSON Body format for POST/PUT:
 * {
 *     "name": "John Doe",
 *     "email": "john@example.com",
 *     "phone": "1234567890",
 *     "address": "123 Main St"
 * }
 */
?>