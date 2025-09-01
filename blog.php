<?php
require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    placeComment();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sam Delhaye">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <title>Blog</title>
</head>

<body id="blog">
    <?php htmlHead(); ?>

    <div class="blogWrapper">
        <aside id="sidebar">
            <div id="blogCategory">
                <h2>Categories</h2>
                <?php displayBlogCategories(); ?>
                <p>Wanna create your own blog?</p>
                <a href="placeBlog.php">Click Here!</a>
            </div>
        </aside>

        <main id="blogMain">
            <?php displayBlogPosts(); ?>
        </main>
    </div>

    <script src="js/main.js"></script>
    <script src="validate.js"></script>
</body>
</html>
<?php htmlFooter(); ?>