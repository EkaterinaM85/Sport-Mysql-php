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
        table {
            border-collapse: collapse;
            width: 95%;
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
        div[for="userSelect"] {
    margin-bottom: 30px;
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
        // SQL-query for the names of all users allowed only to the administrator
        if ($is_admin) {
            $users_query = "SELECT DISTINCT username FROM kaikkitiedot";
            $users_result = $mysqli->query($users_query);
    ?>
    <br>
    <!--Form on the user selection page-->
    <form method="post">
        <div>
            <label for="userSelect">Valitse käyttäjä:</label>
            <select name="userSelect" id="userSelect">
                <?php
                    while ($user_row = $users_result->fetch_assoc()) {
                        $selected = ($user_row['username'] == $_POST['userSelect']) ? 'selected' : '';
                        echo "<option value='{$user_row['username']}' {$selected}>{$user_row['username']}</option>";
                    }
                ?>
            </select>
        </div>
        <br>
        <!--Button to start searching information for the selected user-->
        <div class="buttons">
            <button type="submit" name="submit">Hae käyttäjän tiedot</button>
        </div>
    </form>

    <?php
        if (isset($_POST['submit'])) {
            $selected_user = $_POST['userSelect'];
            // request for all information for the user selected by the administrator
            $user_data_query = "SELECT * FROM kaikkitiedot WHERE username = '$selected_user'";
            // Execute an SQL query and store the result in the $user_data_result variable
            $user_data_result = $mysqli->query($user_data_query);
            // Checks whether there are rows that match the query. Displaying the result in a table
            if ($user_data_result->num_rows > 0) {
                echo "<h3>Käyttäjän {$selected_user} yhteenveto ajoista:</h3>";
                echo "<table>";
                // Outputting a table row containing column headers (<th>)
                echo "<tr><th style='width: 20%;'>Käyttäjä</th><th style='width: 18%;'>Laji</th><th style='width: 25%;'>Aika ja Yksikkö</th><th style='width: 12%;'>Päivämäärä ja kello</th><th style='width: 20%;'>Kommentti</th></tr>";
                // In a loop we print lines that satisfy the request
                while ($row = $user_data_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['laji_kuvaus']}</td>";
                    // Combining information from Aikamaara and Aikayksikko columns
                    $combinedInfo = $row['aikamaara'] . " " . $row['yksikko_kuvaus'];
                    // If Aikayksikko is in "askelta", convert to minutes, rounded to two decimal places
                    if ($row['yksikko_kuvaus'] == 'askelta') {
                        $askeltaValue = number_format($row['aikamaara'] / 130, 2);
                        $combinedInfo .= " / " . $askeltaValue . " minuuttia";
                    }
                    echo "<td>{$combinedInfo}</td>";
                    // Output date and time in a specific format
                    $date = date_create($row['pvmklo']);
                    echo "<td>" . date_format($date, "d.m.Y H:i:s") . "</td>";
                    echo "<td>{$row['kommentti']}</td>";
                    echo "</tr>";
                }
                echo "</table>";

            // Calculation of the sum of time for each sport for a user selected by the administrator
            $sql = "SELECT laji_kuvaus, SUM(aikamaara) as summa, yksikko_kuvaus FROM kaikkitiedot WHERE username = '$selected_user' GROUP BY laji_kuvaus, yksikko_kuvaus";
            // Execute an SQL query and store the result in the $result variable
            $result = $mysqli->query($sql);
            // Checks whether there are rows that match the query. Displaying the result in a table
            if ($result->num_rows > 0) {
                echo "<table>";
                // Outputting a table row containing column headers (<th>)
                echo "<tr><th style='width: 10%;'>Laji</th><th style='width: 20%;'>Yhteensä</th></tr>";
                $totals = 0; // Variable to store the total amount
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
                    if ($row['yksikko_kuvaus'] == 'askelta') {
                        $totals += $askeltaValue;
                    } else {
                        $totals += $row['summa'];
                    }
                }
                // We display the total amount of time
                echo "<tr><td><b>Yhteensä</b></td>";
                echo "<td>{$totals} minuuttia</td></tr>";
                echo "</table>";
            } else {
                echo "Ei löytynyt tietoja käyttäjän {$selected_user} yhteenvetoa varten.";
            }
            } else {
                echo "Käyttäjän {$selected_user} tiedot eivät löytyneet.";
            }
        }
        } else {
            echo "Sinulla ei ole oikeuksia nähdä tätä sivua.";
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
