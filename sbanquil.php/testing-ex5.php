<!DOCTYPE html>
<html>
<head>
    <title>Exercise 5 - Chess Board</title>
</head>
<body>
    <h2>Chess Board</h2>
    <table width="400px" style="border-collapse: collapse;">
        <?php
            $rows = 8;
            $cols = 8;

            for ($i = 1; $i <= $rows; $i++) {
                echo "<tr>";
                for ($j = 1; $j <= $cols; $j++) {
                    if (($i + $j) % 2 == 0) {
                        $color = "#000000"; // black square
                    } else {
                        $color = "#FFFFFF"; // white square
                    }
                    echo "<td style='width:30px; height:30px; background-color:$color; border:1px solid #ccc;'></td>";
                }
                echo "</tr>";
            }
        ?>
    </table>
</body>
</html>
