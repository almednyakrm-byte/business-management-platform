**create_التسويق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">إضافة تسويق جديد</h1>

    <form id="create-taswiq-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم التسويق</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف التسويق</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">صورة التسويق</label>
                <input type="file" id="image" name="image" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">حالة التسويق</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
                    <option value="">اختر حالة</option>
                    <option value="active">نشط</option>
                    <option value="inactive">مغلق</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ التسويق</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-taswiq-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '../backend/التسويق.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_التسويق.php';
                    } else {
                        alert('حدث خطأ أثناء الحفظ');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/التسويق.php**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['image']) && isset($_POST['status'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
    $status = $_POST['status'];

    $query = "INSERT INTO التسويق (name, description, image, status) VALUES ('$name', '$description', '$image', '$status')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>