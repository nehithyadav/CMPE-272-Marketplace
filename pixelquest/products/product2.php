<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: /login.php');
    exit();
}

$productID = 2;

// MOST VIEWED COOKIE
$mostViewedCookieName = 'product_view_count_' . $productID . '_' . md5($_SESSION['userid']);
$currentCount = isset($_COOKIE[$mostViewedCookieName]) ? intval($_COOKIE[$mostViewedCookieName]) : 0;
setcookie($mostViewedCookieName, $currentCount + 1, time() + (86400 * 30), "/");

// RECENTLY VIEWED COOKIE
// Construct the cookie name based on the user ID
$cookieName = 'recently_viewed_' . md5($_SESSION['userid']);
// Read the recently viewed products from the user-specific cookie
$recentlyViewed = isset($_COOKIE[$cookieName]) ? explode(',', $_COOKIE[$cookieName]) : [];
// Add the current product to the start of the list
array_unshift($recentlyViewed, $productID);
// Remove duplicates
$recentlyViewed = array_unique($recentlyViewed);
// Ensure we only store the top 5
$recentlyViewed = array_slice($recentlyViewed, 0, 5);
// Save the updated list back to the cookie
setcookie($cookieName, implode(',', $recentlyViewed), time() + (86400 * 30), "/"); // Cookie will expire after 30 days

// Global RECENTLY VIEWED COOKIE
$cookieName = 'global_recently_viewed_' . md5($_SESSION['userid']);
// Read the recently viewed products from the user-specific cookie
$recentlyViewed = isset($_COOKIE[$cookieName]) ? explode(',', $_COOKIE[$cookieName]) : [];
// Add the current product to the start of the list
array_unshift($recentlyViewed, $productID);
// Remove duplicates
$recentlyViewed = array_unique($recentlyViewed);
// Ensure we only store the top 5
$recentlyViewed = array_slice($recentlyViewed, 0, 5);
// Save the updated list back to the cookie
setcookie($cookieName, implode(',', $recentlyViewed), time() + (86400 * 30), "/"); // Cookie will expire after 30 days

// Global MOST VIEWED COOKIE
$mostViewedCookieName = 'global_product_view_count_' . $productID . '_' . md5($_SESSION['userid']);
$currentCount = isset($_COOKIE[$mostViewedCookieName]) ? intval($_COOKIE[$mostViewedCookieName]) : 0;
setcookie($mostViewedCookieName, $currentCount + 1, time() + (86400 * 30), "/");

// Average Rating 
// Assuming $productID contains the ID of the current product
require_once '../../db_connection.php';
$avgRatingQuery = "SELECT AVG(rating) as average_rating FROM product_reviews WHERE product_id = $productID";
$avgResult = $link->query($avgRatingQuery);

if ($avgResult) {
    $avgRow = $avgResult->fetch_assoc();
    $averageRating = round($avgRow['average_rating'], 1); // Round to one decimal place
} else {
    $averageRating = "No Ratings";
}
$link->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marvel's Spider-Man 2</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="topnav">
    <a href="../index.php">
      <img src="../images/logo.png" alt="Pixel Quest Games">
    </a>
    <h2>Marvel's Spider-Man 2</h2>
</div>
<div class="product">
    <img src="../images/spiderman2.jpeg" alt="Spider-Man 2 Cover Art">
    <h2>Spider-Man 2</h2>
    <h4>Rating: <?php echo $averageRating; ?></h4>
    <p>
    Marvel's Spider-Man 2 is a single player game. But you can play as 
    both Peter Parker and Miles Morales, switching between the heroic Spider-Men on your crime-fighting 
    adventures to experience their individual gameplay abilities and story elements.
    </p>
    <button class="button">Buy Now</button>
    <br><br><br>
    <button class="button" onclick="showReviews()">Show Reviews</button>
    <div class="review-section">
    <h3>Add Your Review</h3>
    <form action="../submit_review.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
        <label for="rating">Rating:</label>
        <select name="rating" id="rating">
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select>
        <br><br>
        <label for="review">Review:</label>
        <br>
        <textarea name="review" id="review" required></textarea>
        <br><br>
        <button class="button" type="submit">Submit Review</button>
    </form>
</div>
</div>
<script>
function showReviews() {
    // Logic to display reviews - this could be a redirection to a new page or a pop-up
    window.location.href = '../reviews.php?product_id=' + <?php echo $productID; ?>;
}
</script>
</body>
</html>
