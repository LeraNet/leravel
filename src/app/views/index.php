<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        body {
            background-image: linear-gradient(40deg, #744d4d, #ffffff);
            font-size: 1.2rem;
            line-height: 1.5;
            color: black;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }

        h1 {
            font-size: 2rem;
            font-weight: 400;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -1rem;
        }

        .col {
            flex: 1 1 100%;
            padding: 0 1rem;
        }

        .center {
            text-align: center;
        }

        .centerbox {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }

        .btn:hover {
            background-color: #444;
        }

        .btn img {
            width: 2.5rem;
        }

        .logo {
            width: 100%;
            max-width: 300px;
        }

        ::-webkit-scrollbar {
            width: 0.4rem;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        .langChanger {
            display: block;
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: center;
            background-color: #333;
            border: 1px solid #686868;
            padding: 10px;
            border-radius: 10px;
            color: white;
            box-shadow: 0px 0px 10px 3px black;
            transition: 0.2s all ease-in-out;
        }

        .langChanger:hover {
            transform: scale(1.03);
        }

        .langs {
            display: flex;
            gap: 10px;
        }

        .langs a {
            background-color: #4a4a4a;
            border: 1px solid #6e6e6e;
            padding: 10px;
            border-radius: 10px;
            color: white;
            width: 50px;
            text-align: center;
            text-decoration: none;
            transition: 0.2s all ease-in-out;
        }

        .langs a:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div class="langChanger">
        <p>{[changeLang]}</p>
        <div class="langs">
            <?php
            array_shift($Leravel["settings"]["lang"]);
            foreach ($Leravel["settings"]["lang"] as $lang) {
                echo '<a href="/lang/' . $lang . '">' . $lang . '</a>';
            }
            ?>
        </div>
    </div>
    <div class="centerbox">
        <img src="https://cdn.discordapp.com/attachments/989920686065725490/1097604721369423953/leravellogo.png" alt="" class="logo" draggable="false">
        <h1 class="center">Leravel</h1>
        <p class="center">{[about]}</p>
        <a href="https://github.com/lera2od/leravel" class="btn"><img src="https://img.icons8.com/fluency/256/github.png">Github</a>
    </div>
</body>

</html>