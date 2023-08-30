<header>
    <a href="/?admin"><h1><img src="<?= $icons["admin"]?>">leravel admin</h1></a>
    <div>
        <div class="dropdown"><p onClick="window.location.href = '//localhost:8000/?admin&route=tool&tool=users&user=<?= $_SESSION['username'] ?>'"><?php $fancyName = $_SESSION["username"];
        $fancyName[0] = strtoupper($fancyName[0]);
        echo $fancyName; ?></p> <img src="<?= $icons["dropdown"]?>">
            <div class="dropdown-content">
                <a href="/?admin&route=logout">Logout</a>
            </div> 
        </div>
    </div>
</header>