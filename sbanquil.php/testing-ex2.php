<!DOCTYPE html>
<html>
<head>
    <title>Exercise 2 - Factorial (For Loop)</title>
</head>
<body>
    <h2>Factorial Using For Loop</h2>
    <?php
        $number = 5; // change this value to test other numbers
        $factorial = 1;

        for ($i = 1; $i <= $number; $i++) {
            $factorial *= $i;
        }

        echo "Factorial of $number is $factorial";
    ?>
</body>
</html>
