<div class="sidebar">
    <ul>
        <li><a href="/?admin&route=/"><img src="<?= $icons["home"]?>">Home</a></li>
        <li><a href="/?admin&route=database"><img src="<?= $icons["database"]?>">Database</a></li>
        <li><a href="/?admin&route=localization"><img src="<?= $icons["localization"]?>">Localization</a></li>
        <li><a href="/?admin&route=stats"><img src="<?= $icons["stats"]?>">Stats</a></li>
        <li><a href="/?admin&route=settings"><img src="<?= $icons["settings"]?>">Settings</a></li>
        <li><a href="/?admin&route=update"><img src="<?= $icons["update"]?>">Update</a></li>
    </ul>
</div>

<div class="mobile-sidebar">
    <select name="" id="selectPage" onchange="navigate()">
        <option value="">Select Page</option>
        <option value="/?admin&route=/">Home</option>
        <option value="/?admin&route=database">Database</option>
        <option value="/?admin&route=localization">Localization</option>
        <option value="/?admin&route=settings">Settings</option>
        <option value="/?admin&route=update">Update</option>
    </select>
</div>

<script>
    let selectPage = document.querySelector("#selectPage");

    function navigate() {
        window.location.href = selectPage.value;
    }
</script>

<?php include $_SERVER["DOCUMENT_ROOT"] . "/leravel/admin/views/checkUpdate.php" ?>