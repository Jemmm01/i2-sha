<?php
@include 'dbconnect.php';

// Fetch categories from the database
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the error, for now, let's assume an empty array
    $categories = array();
}

// Fetch items and their prices from the database
$itemQuery = "SELECT DISTINCT item_name, item_price FROM items";
$itemResult = mysqli_query($conn, $itemQuery);

// Check if the query was successful
if ($itemResult) {
    $items = mysqli_fetch_all($itemResult, MYSQLI_ASSOC);
} else {
    // Handle the error, for now, let's assume an empty array
    $items = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donualk Order Page</title>
    <style>
        /* Your existing styles here */
    </style>
</head>
<body>
    <header>
        <h1>Donutalk Order Page</h1>
    </header>

    <section>
        <div id="cat_name" class="step">
            <h2>Choose Category</h2>
            <select name="cat_name" onchange="generateDonutOptions()">
                <?php foreach ($categories as $category): ?>
                    <option value='<?php echo $category['cat_name']; ?>' data-qty='<?php echo $category['cat_qty']; ?>'>
                        <?php echo $category['cat_name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="donutOptionsContainer" class="step">
            <!-- Donut options dynamically generated based on user selection -->
        </div>
    </section>

    <section>
        <div id="totalAmount">Total Amount: ₱0.00</div>
        <button onclick="proceedToCheckout()"><a href= "payment.php">Proceed to Checkout</a></button>
        <a href= "payment.php"></a>
    </section>

    <script>
        function updateTotalAmount() {
            var selectedItemPrices = Array.from(document.getElementsByClassName("donutSelect")).map(select => parseFloat(select.value));
            var totalAmount = selectedItemPrices.reduce((sum, price) => sum + price, 0);
            document.getElementById("totalAmount").innerText = "Total Amount: ₱" + totalAmount.toFixed(2);
        }

        function proceedToCheckout() {
            // Implement your checkout logic here
        }

        function generateDonutOptions() {
            var categorySelect = document.querySelector('[name="cat_name"]');
            var donutOptionsContainer = document.getElementById("donutOptionsContainer");
            var donutCount = parseInt(categorySelect.options[categorySelect.selectedIndex].getAttribute('data-qty')) || 0;

            // Clear existing options
            donutOptionsContainer.innerHTML = "";

            // Add new options
            for (var i = 1; i <= donutCount; i++) {
                var donutOption = document.createElement("div");
                donutOption.innerHTML = `
                    <h2>Donut ${i}</h2>
                    <select name="donuts[]" class="donutSelect" onchange="updateTotalAmount()">
                        <?php foreach ($items as $item): ?>
                            <option value='<?php echo $item['item_price']; ?>'>
                                <?php echo $item['item_name']; ?> - ₱<?php echo $item['item_price']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                `;
                donutOptionsContainer.appendChild(donutOption);
            }
        }
    </script>
</body>
</html>