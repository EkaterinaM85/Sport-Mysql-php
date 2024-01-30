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
            width: 90%;
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
            width: 100px;
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
            // SQL-query to select data depending on user status
            if ($is_admin) {
                // If the user is an administrator, query the amount of exercises for each sport for all users together
                $sql = "SELECT laji_kuvaus, SUM(aikamaara) as summa, yksikko_kuvaus FROM kaikkitiedot GROUP BY laji_kuvaus, yksikko_kuvaus";
            } else {
                // If the user is not an administrator, query the amount of exercises for each sport for this user ("kayttaja" = $kukaid)
                $sql = "SELECT laji_kuvaus, SUM(aikamaara) as summa, yksikko_kuvaus FROM kaikkitiedot WHERE kayttaja LIKE '" . $kukaid . "' GROUP BY laji_kuvaus, yksikko_kuvaus";
            }
                // Execute an SQL query and store the result in the $result variable
                $result = $mysqli->query($sql);
                    // Checks whether there are rows that match the query. Displaying the result in a table
                    if ($result->num_rows > 0) {
                        echo "<table>";
                        // Outputting a table row containing column headers (<th>)
                        echo "<tr><th style='width: 10%;'>Laji</th><th style='width: 20%;'>Yhteensä</th></tr>";
                        // Array to store common values for each sport
                        $totals = []; 
                            // In a loop we print lines that satisfy the request
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['laji_kuvaus'] . "</td>";                            
                                if ($row['yksikko_kuvaus'] == 'askelta') {
                                    $askeltaValue = number_format($row['summa'] / 130, 2); // If "yksikko_kuvaus" is in "askelta", convert to minutes, rounded to two decimal places
                                    echo "<td>" . $row['summa'] . " " . $row['yksikko_kuvaus'] . " / " . $askeltaValue . " minuuttia</td>"; // output format: amount in askelta / amount in minutes 
                                } else {
                                    echo "<td>" . $row['summa'] . " " . $row['yksikko_kuvaus'] . "</td>";
                                }
                                echo "</tr>";
                                // Let's sum up the values for each sport
                                if (!isset($totals[$row['laji_kuvaus']])) {
                                    $totals[$row['laji_kuvaus']] = 0;
                                }

                                if ($row['yksikko_kuvaus'] == 'askelta') {
                                    $totals[$row['laji_kuvaus']] += $askeltaValue;
                                } else {
                                    $totals[$row['laji_kuvaus']] += $row['summa'];
                                }
                            }
                        // Displaying total values for each sport
                        echo "<tr><td><strong>Yhteensä</strong></td><td>" . number_format(array_sum($totals), 2) . " minuuttia</td></tr>";
                        echo "</table>";
                    } else {
                        echo "Ei tuloksia.";
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
