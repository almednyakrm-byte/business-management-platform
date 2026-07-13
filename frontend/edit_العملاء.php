**edit_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/العملاء.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'Edit العميل';

// Include header
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $existingRecord['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $existingRecord['email'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $existingRecord['phone'] ?>">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/العملاء.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Create AJAX PUT request
        fetch('../backend/العملاء.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value
            })
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>

**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-white shadow-md p-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold text-emerald-600">Logo</a>
            <ul class="flex items-center space-x-4">
                <li><a href="dashboard.php" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a></li>
                <li><a href="logout.php" class="text-sm font-medium text-gray-700 hover:text-gray-900">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>

**footer.php**

<footer class="bg-white shadow-md p-4">
    <div class="container mx-auto text-center">
        &copy; 2023 <?= $pageTitle ?>
    </div>
</footer>

Note: Replace `<?= $mod_slug ?>` with the actual value of the `$mod_slug` variable. Also, make sure to update the `../backend/العملاء.php` file to handle PUT requests and update the existing record accordingly.