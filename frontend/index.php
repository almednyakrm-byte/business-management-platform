<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة إدارة أعمال مع تتبع المبيعات والتسويق والعملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">منصة إدارة أعمال مع تتبع المبيعات والتسويق والعملاء</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً <?php echo $_SESSION['username']; ?></h2>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات عامة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد العملاء</h3>
                    <p id="customers-count" class="text-lg text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">مبيعات اليوم</h3>
                    <p id="today-sales" class="text-lg text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">مبيعات الشهر</h3>
                    <p id="monthly-sales" class="text-lg text-gray-600"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold text-emerald-600">روابط سريعة</h2>
            <ul class="list-none p-0 m-0">
                <li class="mb-2">
                    <a href="customers.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">العملاء</a>
                </li>
                <li class="mb-2">
                    <a href="sales.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">المبيعات</a>
                </li>
                <li class="mb-2">
                    <a href="marketing.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">التسويق</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('customers-count').innerHTML = data.customersCount;
                document.getElementById('today-sales').innerHTML = data.todaySales;
                document.getElementById('monthly-sales').innerHTML = data.monthlySales;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a PHP backend with an API endpoint `/api/stats` that returns a JSON object with the stats data. You'll need to replace this with your actual API endpoint and data structure.

Also, make sure to update the `logout.php` file to handle the logout logic.

Note: This code uses the `fetch` API to make a GET request to the API endpoint. If you're using an older browser that doesn't support the `fetch` API, you may need to use a library like Axios or jQuery to make the request.