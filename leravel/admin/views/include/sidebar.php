<div class="sidebar">
    <ul>
        <li><a href="/?admin&route=/">Home</a></li>
        <li><a href="/?admin&route=database">Database</a></li>
        <li><a href="/?admin&route=localization">Localization</a></li>
        <li><a href="/?admin&route=settings">Settings</a></li>
        <li><a href="/?admin&route=update">Update</a></li>
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