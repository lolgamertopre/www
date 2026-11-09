<!DOCTYPE html>
<html>
<head>
    <title>Exercise 4 - Star Pattern</title>
    <style>
        body { font-family: monospace; }
    </style>
</head>
<body>
    <h2>Star Pattern</h2>
    <?php
        $rows = 8;

        for ($i = 1; $i <= $rows; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo "*";
            }
            echo "<br>";
        }
    ?>
</body>
</html>
