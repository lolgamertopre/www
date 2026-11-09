<!DOCTYPE html>
<html>
<head>
    <title>Exercise 3 - Factorial (Recursive)</title>
</head>
<body>
    <h2>Factorial Using Recursive Function</h2>
    <?php
        function factorial($n) {
            if ($n <= 1) {
                return 1;
            }
            return $n * factorial($n - 1);
        }

        $number = 5; // change this value to test other numbers
        echo "Factorial of $number is " . factorial($number);
    ?>
</body>
</html>
