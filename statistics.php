<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        table {
            border-collapse: collapse;
            width: 50%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 150px;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            width: 20%;
        }
        .buttons {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }
        .buttons button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            white-space: nowrap;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }    
    </style>
</head>
<body>
    <?php
        // Connecting to the database
        $mysqli = new mysqli("localhost", "root", "", "liikuntasuorituksia"); 
        $mysqli->set_charset("utf8mb4");
            if ($mysqli->connect_error) {
                die("Virhe yhteyden muodostamisessa tietokantaan: " . $mysqli->connect_error);
            }
        // User identification and provision of personalized access to the site
        session_start();
        $kukaid = $_SESSION["id"];
        // Checking if the current user is an administrator (id 3)
        $is_admin = false;
            if ($kukaid == 3) {
                $is_admin = true;
            }
        // SQL-query allowed only to administrator
        if ($is_admin) {
            $sql = "SELECT username, ROUND(SUM(CASE WHEN yksikko_kuvaus = 'askelta' THEN aikamaara / 130 ELSE aikamaara END), 2) AS total_time FROM kaikkitiedot GROUP BY username";
             // Execute an SQL query and store the result in the $result variable
            $result = $mysqli->query($sql);
            // Checks whether there are rows that match the query. Displaying the result in a table
            if ($result->num_rows > 0) {
                echo "<h3>Käyttäjien yhteenlasketut määrät:</h3>";
                echo "<table>";
                // Outputting a table row containing column headers (<th>)
                echo "<tr><th>Käyttäjä</th><th>Yhteensä</th></tr>";
                $userTotals = []; // An array to store common values for each user
                // In a loop we print lines that satisfy the request
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['total_time'] . " minuuttia</td>";
                    echo "</tr>";
                    // Summarize the values for each user
                    $userTotals[$row['username']] = $row['total_time'];
                }
                echo "</table>";

            // Finding the user with the maximum total amount
            $maxUser = '';
            $maxTotal = 0;
            foreach ($userTotals as $user => $total) {
                if ($total > $maxTotal) {
                    $maxTotal = $total;
                    $maxUser = $user;
                }
            }
            echo "<p><b>Käyttäjä, jolla on suurin yhteensä:</b> {$maxUser} - {$maxTotal} minuuttia</p>";
            } else {
                echo "Ei löytynyt tietoja yhteenvetoa varten.";
        }
        }    
        $mysqli->close();
    ?>
    <!--Return to previous page-->
        <form action="listaa.php">
            <div class="buttons" style="margin-top: 10px;">
                <button type="submit">Takaisin</button>
            </div>
        </form>
</body>
</html>
