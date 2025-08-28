<?php
require_once 'classes/User.php';

$user = new User();
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone' => trim($_POST['phone']),
                    'address' => trim($_POST['address'])
                ];
                
                // Validate email
                if ($user->emailExists($data['email'])) {
                    $message = 'Email already exists!';
                    $messageType = 'error';
                } else {
                    $result = $user->create($data);
                    if ($result) {
                        $message = 'User created successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Failed to create user!';
                        $messageType = 'error';
                    }
                }
                break;
                
            case 'update':
                $id = intval($_POST['id']);
                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone' => trim($_POST['phone']),
                    'address' => trim($_POST['address'])
                ];
                
                // Validate email (excluding current user)
                if ($user->emailExists($data['email'], $id)) {
                    $message = 'Email already exists!';
                    $messageType = 'error';
                } else {
                    $result = $user->update($id, $data);
                    if ($result) {
                        $message = 'User updated successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Failed to update user!';
                        $messageType = 'error';
                    }
                }
                break;
        }
    }
}

// Handle delete requests
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $result = $user->delete($id);
    if ($result) {
        $message = 'User deleted successfully!';
        $messageType = 'success';
    } else {
        $message = 'Failed to delete user!';
        $messageType = 'error';
    }
}

// Get user for editing
$editUser = null;
if (isset($_GET['edit'])) {
    $editUser = $user->read(intval($_GET['edit']));
}

// Get all users
$users = $user->readAll();

// Handle search
$searchTerm = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    $users = $user->search($searchTerm);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adsindia - User Management CRUD</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-users"></i> Adsindia User Management</h1>
            <p>Complete CRUD Operations - Create, Read, Update, Delete</p>
        </header>

        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- User Form -->
        <div class="form-section">
            <h2><?php echo $editUser ? 'Edit User' : 'Add New User'; ?></h2>
            <form method="POST" class="user-form">
                <input type="hidden" name="action" value="<?php echo $editUser ? 'update' : 'create'; ?>">
                <?php if ($editUser): ?>
                    <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
                <?php endif; ?>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Name</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['name']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Phone</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['phone']) : ''; ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo $editUser ? htmlspecialchars($editUser['address']) : ''; ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas <?php echo $editUser ? 'fa-save' : 'fa-plus'; ?>"></i>
                        <?php echo $editUser ? 'Update User' : 'Add User'; ?>
                    </button>
                    <?php if ($editUser): ?>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <form method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Search by name or email..." 
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if ($searchTerm): ?>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Users List -->
        <div class="users-section">
            <h2>Users List</h2>
            <?php if ($users && count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo $u['id']; ?></td>
                                    <td><?php echo htmlspecialchars($u['name']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><?php echo htmlspecialchars($u['phone']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($u['address'], 0, 50)) . (strlen($u['address']) > 50 ? '...' : ''); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                                    <td class="actions">
                                        <a href="?edit=<?php echo $u['id']; ?>" class="btn btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $u['id']; ?>" class="btn btn-delete" 
                                           onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-users">
                    <i class="fas fa-users"></i>
                    <p><?php echo $searchTerm ? 'No users found matching your search.' : 'No users found. Add your first user above.'; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>