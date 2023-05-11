<?php

include "include/toast.php";

toast("Logged in successfully", "success");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leravel Login</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 0;
            padding: 20px 0;
            background-color: #333;
            color: #fff;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        .captcha {
            display: flex;
            flex-direction: row;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .captcha img {
            width: 100%;
            max-width: 200px;
        }

        .captcha input {
            width: 100%;
            max-width: 200px;
        }

        .toast {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            border-radius: 4px;
            padding: 20px;
            margin: 20px;
            position: fixed;
            top: -100px;
            right: 20px;
            z-index: 9999;
            transition: all 0.5s ease-in-out;
        }

        .toast-success {
            background-color: #d3ffab;
            border: 1px solid #a3ff7b;
            border-radius: 4px;
        }

        .toast-error {
            background-color: #fd9696;
            border: 1px solid #ff9999;
            border-radius: 4px;
        }

        .toast-info {
            background-color: #cdedfc;
            border: 1px solid #b3ebff;
            border-radius: 4px;
        }

        .toast-close:hover {
            background-color: #cccccc;
        }

        p {
            margin: 0;
        }

        a {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        a:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <h1>Leravel Login</h1>
    <br>
    <p>Logged in successfuly</p>
    <br>
    <a href="/?admin&route=/" class="btn btn-primary">Go to admin panel</a>
</body>

</html>