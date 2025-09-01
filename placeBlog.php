<?php

require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    placeBlog();
}

if (!isset($_SESSION['user'])) {
    echo '<script>alert("Please log to place a blog"); window.location.href = "login.php";</script>';
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dominika Debska">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <title>Place Blog</title>
</head>

<body id="blog">
    <?php
    htmlHead();
    ?>
    <main class="blog-main">
    <form class="blog-form" onsubmit="return validateForm()" method="POST">
        <h2 class="blog-title">Place Blog</h2>

        <label for="title" class="blog-label"><b>Title</b></label>
        <input type="text" name="title" class="blog-input" placeholder="Blog Title" required>

        <label for="category" class="blog-label"><b>Category</b></label>
        <select name="category" class="blog-select" required>
            <option value="">Select a category</option>
            <option value="1">New Releases</option>
            <option value="2">Game Reviews</option>
            <option value="3">Console Reviews</option>
        </select>

        <label for="post" class="blog-label"><b>Blog Post</b></label>
        <textarea name="post" class="blog-textarea" placeholder="Blog Post" required></textarea>

        <button type="submit" class="blog-btn">Submit</button>
    </form>
    </main>
    <?php
    htmlFooter();
    ?>
    <script src="validate.js"></script>
</body>
</html>