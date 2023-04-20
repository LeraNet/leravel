<?php 

function toast($message, $type = "success") {
    echo "<div class='toast toast-$type'>$message</div>";
    echo "<script>
    setTimeout(() => {
        document.querySelector('.toast').style.top = '20px';
        setTimeout(() => {
            document.querySelector('.toast').style.right = '-100%';
        }, 3000);
    }, 500);
    </script>";
}