<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_POST["test"])) {
        echo '<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span><strong>Danger!</strong> Indicates a dangerous or potentially negative action.</div>';
    }
    ?>

    <form action="./test.php" method="post">
        <button type="submit" name="test" value="helpme">gay</button>
    </form>



    <div class="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
    </div>

</body>

</html>