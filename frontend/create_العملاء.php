**create_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Get module slug
$mod_slug = 'العملاء';

// Get form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert data into database
        $sql = "INSERT INTO العملاء (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $email, $phone, $address);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_' . $mod_slug . '.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind CSS
?>

<style>
    .bg-emerald-600 {
        background-color: #03C75A;
    }
    .text-teal-500 {
        color: #0097A7;
    }
</style>

<!-- Create form -->
<div class="max-w-md mx-auto p-4 bg-emerald-600 rounded-md shadow-md">
    <h2 class="text-lg font-bold text-white mb-4">Create New العملاء</h2>
    <form action="" method="post" id="create-form">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-white">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="John Doe">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-white">Email:</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="john.doe@example.com">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-white">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="+1234567890">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-white">Address:</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500" placeholder="123 Main St, Anytown, USA"></textarea>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-teal-500 hover:bg-teal-700 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // Submit form via AJAX
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '../backend/العملاء.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_' + '<?php echo $mod_slug; ?>' + '.php';
                } else {
                    alert('Error creating new العملاء.');
                }
            }
        });
    });
</script>

Note: This code assumes you have a `database.php` file that connects to your database and a `backend/العملاء.php` file that handles the form submission. You'll need to modify the code to fit your specific database schema and backend logic.