<?php
if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json")) {
    file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json", json_encode(array()));
}

$stats = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/app/stats.json"), true);

$weeklyVisits = array();

foreach ($stats as $stat) {
    $date = $stat["date"];
    $visits = 0;
    foreach ($stats as $stat2) {
        if ($stat2["date"] == $date) {
            $visits++;
        }
    }
    $weeklyVisits[] = [
        "date" => $date,
        "visits" => $visits
    ];
}

$mostVisitedPages = array();

foreach ($stats as $stat) {
    $page = $stat["url"];
    $visits = 0;
    foreach ($stats as $stat2) {
        if ($stat2["url"] == $page) {
            $visits++;
        }
    }
    if(in_array($page, array_column($mostVisitedPages, "page"))) {
        continue;
    }
    $mostVisitedPages[] = [
        "page" => $page,
        "visits" => $visits
    ];
}

usort($mostVisitedPages, function($a, $b) {
    return $b["visits"] <=> $a["visits"];
});

$visitors = array();

foreach ($stats as $stat) {
    $ip = $stat["ip"];
    $visits = 0;
    foreach ($stats as $stat2) {
        if ($stat2["ip"] == $ip) {
            $visits++;
        }
    }
    if(in_array($ip, array_column($visitors, "ip"))) {
        continue;
    }
    if($ip == "127.0.0.1") {
        continue;
    }
    $country = json_decode(file_get_contents("http://ip-api.com/json/" . $ip), true)["country"];
    $city = json_decode(file_get_contents("http://ip-api.com/json/" . $ip), true)["city"];
    $region = json_decode(file_get_contents("http://ip-api.com/json/" . $ip), true)["regionName"];
    $url = $stat["url"];
    $visitors[] = [
        "ip" => $ip,
        "visits" => $visits,
        "country" => $country,
        "city" => $city,
        "region" => $region,
        "time" => $stat["time"],
        "url" => $url
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Admin Stats</title>
    <link rel="stylesheet" href="/?admin&route=css&<?= time() ?>">
</head>

<body>
    <?php include "include/header.php" ?>
    <?php include "include/sidebar.php" ?>
    <div class="content">
        <h1><img src="<?= $icons["stats"] ?>" draggable="off">Statistics</h1>
        <div class="tab-content">
            <h2>Weekly Visits</h2>
            <canvas id="weeklyVisits" style="width:100%;max-width:700px"></canvas>
            <h2>Most Visited Pages</h2>
            <table>
                <tr>
                    <th>Page</th>
                    <th>Visits</th>
                </tr>
                <?php
                foreach ($mostVisitedPages as $page) {
                ?>
                    <tr>
                        <td><?= $page["page"] ?></td>
                        <td><?= $page["visits"] ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <h2>Visitors</h2>
            <table>
                <tr>
                    <th>IP</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Region</th>
                    <th>Time</th>
                    <th>URL</th>
                </tr>
                <?php
                $person = 0;
                foreach ($visitors as $stat) {
                $person++;
                if($person > 30) {
                    echo "<tr><td colspan='6'>...</td></tr>";
                    break;
                }
                ?>
                    <tr>
                        <td><?= $stat["ip"] ?></td>
                        <td><?= $stat["country"] ?></td>
                        <td><?= $stat["city"] ?></td>
                        <td><?= $stat["region"] ?></td>
                        <td><?= $stat["time"] ?></td>
                        <td><?= $stat["url"] ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>

        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
        </script>
        <script>
            <?php
            $labels = array();
            $data = array();
            foreach ($weeklyVisits as $visit) {
                $labels[] = $visit["date"];
                $data[] = $visit["visits"];
            }
            ?>
            const weeklyVisits = new Chart("weeklyVisits", {
                type: "line",
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: "Visits",
                        data: <?= json_encode($data) ?>,
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1
                    }]
                },
                options: {}
            });
        </script>
    </div>
</body>

</html>