<?php
include 'Functions.php';

?>
<body id="category-<?php echo isset($_GET['categoryId']) ? $_GET['categoryId'] : 'default'; ?>">
<?php
htmlHead("Gameworld");
displayCategories();
?>
    <div class="gif-wrapper">
    <img src="https://i.pinimg.com/originals/d3/9d/8d/d39d8d0415b649af9705b6d39c564c55.gif" alt="Blue waves" />
    </div>
<?php
?>
    <div class="popular-games">
    <p>- Popular games -</p>
    </div>
<?php
displayPopularGames();
?>
    <div class="other-games">
    <p>- Games ! -</p>
    </div>
<?php
displayGames();

htmlFooter();
?>
</body>