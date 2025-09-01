<?php

/**
 * @param string $pageTitle
 * @return void
 */
// Start a new or resume existing session
session_start();
// Function to generate HTML head and header section
function htmlHead()
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <!-- Set character encoding to UTF-8 -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Ensure responsive design -->
        <title>Gameworld</title>
        <!-- Set page title -->
        <link rel="stylesheet" href="style.css" />
        <!-- Link to external CSS stylesheet -->
        <link rel="icon" type="image/png" href="">
        <!-- Set favicon (currently empty href) -->
    </head>

    <body>
        <header id="Header">
            <!-- Site header with logo and navigation -->
            <a href="index.php">
                <div id="Logo">Gameworld</div>
            </a>
            <nav id="Main-nav">
                <ul>
                    <?php
                    // Retrieve navigation items from database
                    $navItems = getHeader();
                    // Loop through navigation items
                    foreach ($navItems as $navItem) 
                    {
                    ?>
                        <li><a href="<?php echo $navItem['pageFile']; ?>"><?php echo $navItem['pageName']; ?></a></li>
                        <!-- Display navigation links -->
                    <?php
                    }
                    // Check if user is logged in
                    if (isset($_SESSION['user'])) 
                    {
                    ?>
                        <li><a href="logout.php">Logout</a></li>
                        <!-- Show logout link for logged-in users -->
                    <?php
                    } 
                    else 
                    {
                    ?>
                        <li><a href="login.php">Login</a></li>
                        <!-- Show login link for non-logged-in users -->
                    <?php
                    }
                    ?>
                </ul>
            </nav>
        </header>
    <?php
}

// Function to generate HTML footer
function htmlFooter()
{
    ?>
        <footer id="Footer">
            <!-- Site footer with designer credit and download link -->
            <p>Designed by <a href="https://omori.fandom.com/wiki/KEL">Dominik Debska</a></p>
            <a href="Astroidinator2.zip" download>
            <button>Download PDF</button></a>
        </footer>
    </body>
    </html>
    <?php
}

// Function to establish database connection
function db_connect()
{
    // Database connection parameters
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "gameworld";

    // Create new MySQLi connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to retrieve navigation items from database
function getHeader()
{
    $db = db_connect();
    // Query to select all navigation items
    $sql = "SELECT * FROM navigation ORDER BY navId ASC";
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $navigation = $resource->fetch_all(MYSQLI_ASSOC);

    return $navigation;
}

// Function to display a single game's details
function displaySingleGame()
{
    // Get game details by ID
    $product = getGame($_GET['gameId']);

    // Check if product exists
    if ($product != null) 
    {
        // Set default quantity or use posted value
        $productQuantity = isset($_POST['gameQuantity']) ? $_POST['gameQuantity'] : 1;
    ?> 
    <div class="product-single">
    <div class="product-flexbox"> <!-- ADD THIS LINE -->
        <div class="product-image-container-single">
            <!-- Display game image -->
            <img class="productImage" src='images/<?php echo $product['gameImage']; ?>' />
        </div>
        <div class="productDetailsSingle">
            <!-- Display game details -->
            <h3><?php echo $product['gameName'] ?></h3>
            <p class="gameText"><?php echo $product['gameDetails'] ?></p>
            <p>Price: <?php echo $product['gamePrice'] ?>$</p>
            <!-- Form to add game to cart -->
            <form action="Cart.php" method="POST">
                <label for="quantity" class="quantity-label">Quantity</label>
                <input type="number" min="1" id="quantity" name="gameQuantity" class="form-control" required="required" value="<?php echo $productQuantity; ?>" />
                <input type="hidden" name="gameId" value="<?php echo $product['gameId']; ?>" />
                <button class="add-to-cart" onclick="return confirm('Add this Item to shopping cart?');">Add to shoppingCart</button>
            </form>
        </div>
    </div> 
    </div>
    <?php
    } 
    else 
    {
        // Display error if game not found
        echo " No product with id "  . $_GET['gameId'];
    }
}

// Function to retrieve all games from database
function getGames()
{
    $db = db_connect();
    // Query to select all games
    $sql = "SELECT * FROM games ORDER BY gameId ASC";
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $products = $resource->fetch_all(MYSQLI_ASSOC);
    return $products;
}

// Function to retrieve a specific game by ID
function getGame($gameId)
{
    $db = db_connect();
    // Prepare statement to prevent SQL injection
    $stmt = $db->prepare("SELECT * FROM games WHERE gameId = ?");
    $stmt->bind_param("i", $gameId);
    $stmt->execute();

    $result = $stmt->get_result();
    // Fetch single result as associative array
    return $result->fetch_assoc();
}

// Function to retrieve games by category
function getCategoryGame($categoryId) 
{
    // Get category ID from GET parameter
    $categoryId = $_GET['categoryId'];
    $db = db_connect();
    // Query to select games by category
    $sql = "SELECT * FROM games WHERE categoryId = " . $categoryId;
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $categoryGames = $resource->fetch_all(MYSQLI_ASSOC);
    return $categoryGames;
}

// Function to display games (all or by category)
function displayGames()
{
    // Check if category filter is applied
    if (isset($_GET['categoryId']))    
    {
        $categoryId = $_GET['categoryId'];
        // Get games for specific category
        $categoryGames = getCategoryGame($categoryId);
        ?>
        <div class='product-container'>
        <!-- Container for category games -->
        <?php
        // Loop through category games
        foreach ($categoryGames as $categoryGame)
        {
            ?>
                <div class='product'>
                <!-- Individual game container -->
                <a href="productDetails.php?gameId=<?php echo $categoryGame['gameId']; ?>">
                    <img class="productImage" src='images/<?php echo $categoryGame['gameImage']; ?>' />
                </a>
                <div class="productDetails">
                    <!-- Game details -->
                    <h3><?php echo $categoryGame['gameName']; ?></h3>
                    <p>Price: <?php echo $categoryGame['gamePrice']; ?>$</p>
                    <!-- Form to add to cart -->
                    <form action="Cart.php" method="POST">
                        <label for="quantity" class="quantity-label">Quantity</label>
                        <input type="number" min="1" id="quantity" name="gameQuantity" class="form-control" required="required" />
                        <input type="hidden" name="gameId" value="<?php echo $categoryGame['gameId']; ?>" />
                        <button class="add-to-cart" onclick="return confirm('Add this Item to shopping cart?');">
                            Add to shoppingCart
                        </button>
                    </form>
                </div>
            </div>
            <?php
        }
        } 
        else 
        {
        // Get all games if no category specified
        $products = getGames();
        ?>
            <div class='product-container'>
            <!-- Container for all games -->
        <?php
        // Loop through all games
        foreach ($products as $product) 
        {
        ?>
            <div class='product'>
                <!-- Individual game container -->
                <a href="productDetails.php?gameId=<?php echo $product['gameId']; ?>">
                    <img class="productImage" src='images/<?php echo $product['gameImage']; ?>' />
                </a>
                <div class="productDetails">
                    <!-- Game details -->
                    <h3><?php echo $product['gameName']; ?></h3>
                    <p>Price: <?php echo $product['gamePrice']; ?>$</p>
                    <!-- Form to add to cart -->
                    <form action="Cart.php" method="POST">
                        <label for="quantity" class="quantity-label">Quantity</label>
                        <input type="number" min="1" id="quantity" name="gameQuantity" class="form-control" required="required" />
                        <input type="hidden" name="gameId" value="<?php echo $product['gameId']; ?>" />
                        <button class="add-to-cart" onclick="return confirm('Add this Item to shopping cart?');">
                            Add to shoppingCart
                        </button>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
            </div>
        <?php
    }
}

// Function to place an order
function placeOrder()
{
    $conn = db_connect();
    // Get user ID from session
    $userId = $_SESSION['user']['id'];

    // Insert new order
    $sql = "INSERT INTO orders (orderId, orderDate, userId) VALUES (NULL, NOW(), $userId)";

    $resource = $conn->query($sql) or die($conn->error);

    // Get the inserted order ID
    $orderId = $conn->insert_id;

    $values = [];
    // Prepare order items from cart
    foreach ($_SESSION['cart'] as $gameId => $productQuantity) 
    {
        $gameId = (int) $gameId;
        $productQuantity = (int) $productQuantity;
        $values[] = "($orderId, $gameId, $productQuantity)";
    }

    // Insert order items if cart is not empty
    if (!empty($values)) 
    {
        $sql = "INSERT INTO orderproducts (orderId, gameId, gameQuantity) VALUES " . implode(',', $values);
        $result = $conn->query($sql) or die($conn->error);

        // Handle transaction result
        if ($result) 
        {
            $conn->commit();
            echo "<script>alert('Order placed succesfully!'); window.location.href= 'index.php'</script>";
        } 
        else 
        {
            $conn->rollback();
            echo "<script>alert('An error occurred while placing the order. Please try again.'); window.location.href= 'Checkout.php'</script>";
        }
    }

    // Clear cart after order
    unset($_SESSION['cart']);
}

// Function to empty the shopping cart
function emptyCart()
{
    // Remove cart from session
    unset($_SESSION['cart']);
    // Redirect to checkout page
    header("Location: Checkout.php");
    exit;
}

// Function to handle user signup
function signup()
{
    $conn = db_connect();
    // Check if form is submitted
    if (isset($_POST['submit'])) 
    {
        // Sanitize input
        $username = mysqli_real_escape_string($conn, $_POST['user']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['pass']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpass']);

        // Check if username exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        $count_user = mysqli_num_rows($result);

        // Check if email exists
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $count_email = mysqli_num_rows($result);

        // Validate user and email availability
        if ($count_user == 0 && $count_email == 0) 
        {
            // Check if passwords match
            if ($password == $cpassword) 
            {
                // Hash password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                // Insert new user
                $sql = "INSERT INTO users(username, email, password) VALUES ('$username', '$email', '$hash')";
                $result = mysqli_query($conn, $sql);
                if ($result) 
                {
                    echo '<script>
                    alert("thank you for signing up"); 
                    window.location.href= "index.php"
                    </script>';
                }
            } 
            else 
            {
                echo '<script>
            alert("Passwords do not match");
            window.location.href = "signup.php";
            </script>';
            }
            } 
            else 
            {
            // Handle existing username
            if ($count_user > 0) 
            {
                echo '<script>
            window.location.href="signup.php"
            alert("Username already exists");
            </script>';
            }
            // Handle existing email
            if ($count_email > 0) 
            {
                echo '<script>
            window.location.href="signup.php"
            alert("E_mail already exists");
            </script>';
            }
        }
    }
}

// Function to handle user login
function login()
{
    $conn = db_connect();
    session_start();
    // Check if login form is submitted
    if (isset($_POST["uname"]) && isset($_POST["password"])) 
    {
        $uname = $_POST['uname'];
        $password = $_POST['password'];
    }

    // Trim input to avoid leading/trailing spaces
    $uname = trim($uname);
    $password = trim($password);

    // Validate input
    if (empty($uname)) 
    {
        echo '<script>alert("Username is required"); window.location.href = "login.php";</script>';
        exit();
    } 
    elseif (empty($password)) 
    {
        echo '<script>alert("Password is required"); window.location.href = "login.php";</script>';
        exit();
    } 
    else 
    {
        // Prepare statement to check user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $uname);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verify user credentials
        if ($result->num_rows === 1) 
        {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['user'] = [
            'user_name' => $row['username'],
            'id' => $row['id']
        ];
        // Redirect after successful login
        $redirectTo = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
        header('Location: ' . $redirectTo);
        exit();
        } 
        else 
        {
        echo '<script>alert("Incorrect username or password"); window.location.href = "login.php";</script>';
        exit();
        }
        } 
        else 
        {
        echo '<script>alert("Incorrect username or password"); window.location.href = "login.php";</script>';
        exit();
        }
    }
}

// Function to retrieve all categories
function getCategories()
{
    $db = db_connect();
    // Query to select all categories
    $sql = "SELECT * FROM categories ORDER BY categoryId ASC";
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $categories = $resource->fetch_all(MYSQLI_ASSOC);
    return $categories;
}

// Function to display categories
function displayCategories()
{
    // Get all categories
    $categories = getCategories();
    $categoryClass = ""; 

    // Check if category is selected
    if (isset($_GET['categoryId'])) 
    {
        $category = getCategory($_GET['categoryId']);
        if ($category) {
            // Set CSS class based on category
            $categoryClass = strtolower($category['categoryName']);
        }
    }

    // Display category container
    echo "<div class='category-container $categoryClass'>"; 

    // Loop through categories
    foreach ($categories as $category) 
    {
        ?>
        <div id="<?php echo $category['categoryName']; ?>">
            <a href="products.php?categoryId=<?php echo $category['categoryId']; ?>">
                <?php echo $category['categoryName']; ?>
            </a>
        </div>
        <?php
    }

    echo "</div>"; 
}

// Function to retrieve a specific category
function getCategory($categoryId)
{
    $db = db_connect();
    // Query to select category by ID
    $sql = "SELECT * FROM categories WHERE categoryId = $categoryId";
    $resource = $db->query($sql) or die($db->error);
    // Fetch single result
    $category = $resource->fetch_assoc();
    return $category;
}

// Function to retrieve products by category ID
function GetProductsByCategoryId()
{
    $categoryId = $_GET['categoryId'];
    $db = db_connect();
    // Query to select games by category
    $sql = "SELECT * FROM games WHERE categoryId = $categoryId ORDER BY gameId ASC";
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $products = $resource->fetch_all(MYSQLI_ASSOC);
    return $products;
}

// Function to retrieve blog categories
function getBlogCategories()
{
    $conn = db_connect();
    // Query to select all blog categories
    $sql = "SELECT * FROM blogcategories";
    $result = $conn->query($sql) or die($conn->error);
    // Fetch all results as associative array
    $blogCategories = $result->fetch_all(MYSQLI_ASSOC);
    return $blogCategories;
}

// Function to display blog categories
function displayBlogCategories()
{
    // Get all blog categories
    $blogCategories = getBlogCategories();
    // Loop through blog categories
    foreach($blogCategories as $blogCategory) 
    {
        ?>
        <div>
            <a href= "blog.php?blogCategoryId=<?php echo $blogCategory['blogCategoryId']; ?>"><?php echo $blogCategory['blogCategoryName']; ?></a>
        </div>
        <?php
    }
}

// Function to retrieve blog posts by category
function getBlogCategoryPost($blogCategoryId)
{
    $conn = db_connect();
    // Query to select blog posts by category
    $sql = "SELECT * FROM blog WHERE blogCategoryId = " . $blogCategoryId;
    $resource = $conn->query($sql) or die($conn->error);
    // Fetch all results as associative array
    $blogPostsByCategory = $resource->fetch_all(MYSQLI_ASSOC);

    return $blogPostsByCategory;
}

// Function to retrieve all blog posts
function getBlogPosts()
{
    $conn = db_connect();
    // Query to select all blog posts
    $sql = "SELECT * FROM blog";
    $resource = $conn->query($sql) or die($conn->error);
    // Fetch all results as associative array
    $blogPosts = $resource->fetch_all(MYSQLI_ASSOC);

    return $blogPosts;
}

// Function to retrieve a specific blog post
function getBlogPost($blogId)
{
    $conn = db_connect();
    // Query to select blog post by ID
    $sql = "SELECT * FROM blog WHERE blogId = " . $blogId;
    $resource = $conn->query($sql) or die($conn->error);
    // Fetch single result
    $blogPostById = $resource->fetch_assoc();

    return $blogPostById;
}

function displayBlogPosts()
{
    // Check if filtering by blog category
    if (isset($_GET['blogCategoryId'])) 
    {
        $blogCategoryId = $_GET['blogCategoryId'];
        // Get posts for specific category
        $blogPostsByCategory = getBlogCategoryPost($blogCategoryId);
        // Loop through category posts
        foreach ($blogPostsByCategory as $blogPostByCategory) 
        {
            ?>
                <h2 style="color: white; font-size: 1.5em;">
                    <a href="blog.php?blogId=<?php echo $blogPostByCategory['blogId']; ?>" style="color: white;">
                        <?php echo $blogPostByCategory['blogTitle']; ?>
                    </a>
                </h2>
                <article class="blogPosts" style="color: white; font-size: 1.2em;">
                    <?php echo $blogPostByCategory['blogPost']; ?>
                </article>
            <?php
        }
    } 
    // Check if viewing single post
    elseif (isset($_GET['blogId'])) 
    {
        $blogId = $_GET['blogId'];
        // Get specific blog post
        $blogPostById = getBlogPost($blogId);
        // Get comments for post
        $blogComments = getComments($blogId);
        ?>
            <h2 style="color: white; font-size: 1.5em;">
                <?php echo $blogPostById['blogTitle']; ?> By: <?php echo $blogPostById['author']; ?>
            </h2>
                <article class="blogPosts" style="color: white; font-size: 1.2em;">
                    <?php echo $blogPostById['blogPost']; ?><br><?php echo $blogPostById['date']; ?><br>
                    <?php
            // Check if user is logged in for commenting
             if (!isset($_SESSION['user'])) 
            {
                ?>
                    <h2 style="color: yellow; font-size: 1.3em;">
                    <a href="login.php" style="color: yellow;">User must be logged in to comment under a post</a>
                    </h2>
                </article>
                <?php
            } 
            else 
            {
            ?>
            <button id="openFormBtn" style="margin-top: 10px;">Place Comment</button>
            <!-- Comment form popup -->
            <div id="popupForm" hidden>
                <form onsubmit="return validateCommentForm()" method="POST">
                    <label for="comment" style="color: white;"><b>Comment</b></label>
                    <textarea name="comment" placeholder="Comment" required style="width: 100%; margin-top: 5px;"></textarea>
                    <input type="hidden" name="blogId" value="<?php echo $blogId; ?>">
                    <button type="submit" class="btn">Submit</button>
                    <button type="button" class="btn cancel" id="closeFormBtn">Close</button>
                </form>
            </div>
            <?php
            }
            // Display comments
            foreach ($blogComments as $blogComment) 
            {
                ?>
            <article class="comment" style="color: white; font-size: 1.1em;">
                <p><?php echo $blogComment['comment']; ?></p>
                <p>User: <?php echo $blogComment['username']; ?></p>
                <p><?php echo $blogComment['date']; ?></p>
            </article>
            <?php
            }
            } 
            else 
            {
        // Display all blog posts
        $blogPosts = getBlogPosts();
        foreach ($blogPosts as $blogPost) 
        {
            ?>
            <h2 style="color: white; font-size: 1.5em;">
                <a href="blog.php?blogId=<?php echo $blogPost['blogId']; ?>" style="color: white;">
                    <?php echo $blogPost['blogTitle']; ?>
                </a>
            </h2>
            <article class="blogPosts" style="color: white; font-size: 1.2em;">
                <?php echo $blogPost['blogPost']; ?>
            </article>
        <?php
        }
    }
}

// Function to create a new blog post
function placeBlog()
{
    // Get form data
    $categoryId = $_POST['category'] ?? '';
    $blogTitle = trim($_POST['title'] ?? '');
    $blogPost = trim($_POST['post'] ?? '');
    $username = $_SESSION['user']['user_name'];
    $date = date("Y-m-d H:i:s");

    // Validate input
    if (empty($categoryId) || empty($blogTitle) || empty($blogPost)) 
    {
        echo '<script>alert("All fields are required."); window.history.back();</script>';
        exit;
    }

    if (strlen($blogTitle) > 100) 
    {
        echo '<script>alert("Title too long. Max 100 characters."); window.history.back();</script>';
        exit;
    }

    $conn = db_connect();

    // Sanitize input
    $categoryId = mysqli_real_escape_string($conn, $categoryId);
    $blogTitle = mysqli_real_escape_string($conn, $blogTitle);
    $blogPost = mysqli_real_escape_string($conn, $blogPost);

    // Insert new blog post
    $sql = "INSERT INTO blog(blogCategoryId, author, blogTitle, blogPost, date) 
            VALUES('$categoryId', '$username', '$blogTitle', '$blogPost', '$date')";

    $result = mysqli_query($conn, $sql);

    // Handle result
    if ($result) 
    {
        echo '<script>alert("Blog placed successfully!"); window.location.href = "blog.php";</script>';
    } 
    else 
    {
        echo '<script>alert("Something went wrong."); window.history.back();</script>';
    }
}

// Function to retrieve comments for a blog post
function getComments($blogId)
{
    $conn = db_connect();
    // Query to select comments by blog ID
    $sql = "SELECT * FROM blogcomments WHERE blogId = " . $blogId;
    $resource = $conn->query($sql) or die($conn->error);
    // Fetch all results as associative array
    $blogComments = $resource->fetch_all(MYSQLI_ASSOC);

    return $blogComments;
}

// Function to add a comment to a blog post
function placeComment()
{
    // Get form data
    $blogId = $_POST['blogId'] ?? '';
    $comment = trim($_POST['comment'] ?? '');
    $date = date("Y-m-d H:i:s");
    $username = $_SESSION['user']['user_name'];

    // Validate input
    if (empty($blogId) || empty($comment)) 
    {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if (strlen($comment) > 500) 
    {
        echo "<script>alert('Comment is too long (max 500 characters).'); window.history.back();</script>";
        exit;
    }

    $conn = db_connect();

    // Sanitize input
    $blogId = mysqli_real_escape_string($conn, $blogId);
    $comment = mysqli_real_escape_string($conn, $comment);

    // Insert new comment
    $sql = "INSERT INTO blogcomments(blogId, username, comment, date) 
            VALUES('$blogId', '$username', '$comment', '$date')";
    $result = mysqli_query($conn, $sql);

    // Handle result
    if ($result) 
    {
        echo "<script>alert('Comment placed successfully!'); window.location.href = 'blog.php';</script>";
    } 
    else 
    {
        echo "<script>alert('Something went wrong.'); window.history.back();</script>";
    }
}

// Function to retrieve popular games
function GetPopularGames()
{
    $db = db_connect();
    // Query to select games marked as popular
    $sql = "SELECT * FROM games WHERE isPopular = 1";
    $resource = $db->query($sql) or die($db->error);
    // Fetch all results as associative array
    $popularGames = $resource->fetch_all(MYSQLI_ASSOC);
    return $popularGames;
}

// Function to display popular games
function displayPopularGames()
{
    // Get popular games
    $popularGames = GetPopularGames();
    echo "<div class='product-container'>"; // OPEN grid container

    // Loop through popular games
    foreach($popularGames as $popularGame)
    {
        ?>
        <div class='product'>
            <!-- Individual game container -->
            <a href="productDetails.php?gameId=<?php echo $popularGame['gameId']; ?>">
                <img class="productImage" src='images/<?php echo $popularGame['gameImage']; ?>' />
            </a>
            <div class="productDetails">
                <!-- Game details -->
                <h3><?php echo $popularGame['gameName']; ?></h3>
                <p>Price: <?php echo $popularGame['gamePrice']; ?>$</p>
                <!-- Form to add to cart -->
                <form action="Cart.php" method="POST">
                    <label for="quantity" class="product-quantity-label">Quantity</label>
                    <input type="number" min="1" id="quantity" name="gameQuantity" class="form-control" required />
                    <input type="hidden" name="gameId" value="<?php echo $popularGame['gameId']; ?>" />
                    <button class="add-to-cart" onclick="return confirm('Add this Item to shopping cart?');">
                        Add to shoppingCart
                    </button>
                </form>
            </div>
        </div>
        <?php
    }

    echo "</div>"; // CLOSE grid container
}

// Function to retrieve about page information
function getAboutInfo() 
{
    $db = db_connect();
    // Query to select latest about page content
    $sql = "SELECT * FROM about ORDER BY aboutId DESC LIMIT 1";
    $result = $db->query($sql) or die($db->error);
    // Fetch single result
    return $result->fetch_assoc();
}

// Function to display about page
function displayAboutPage() 
{
    $db = db_connect();
    // Query to select latest about page content
    $sql = "SELECT * FROM about ORDER BY aboutId DESC LIMIT 1";
    $result = $db->query($sql) or die($db->error);
    $about = $result->fetch_assoc();

    // Check if about content exists
    if ($about) 
    {
    ?>
        <div class="about-container">
            <div class="about-text">
                <h2><?php echo $about['title']; ?></h2>
                <p><?php echo nl2br($about['content']); ?></p>
            </div>
        </div>

        <?php if (!empty($about['image'])): ?>
            <div class="about-image">
                <!-- Display about page image if available -->
                <img src="images/<?php echo $about['image']; ?>" alt="About Image" />
             </div>
        <?php 
        endif; 
        ?>
    <?php
    } 
    else 
    {
    ?><p>No About page content found</p><?php
    }
}