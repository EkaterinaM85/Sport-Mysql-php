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
            border-collapse: collapse;/* merges the borders of two cells */
            width: 100%;
        }
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            max-width: 150px;
            word-wrap: break-word; /* to control word wrapping */
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
        @media screen and (max-width: 600px) {
            .buttons button {
                width: auto; 
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
            // If the user is an administrator, select all entries
            $sql = "SELECT * FROM kaikkitiedot";
        } else {
            // If the user is not an administrator, select records only for this user ("kayttaja" = $kukaid)
            $sql = "SELECT * FROM kaikkitiedot WHERE kayttaja LIKE '" . $kukaid . "'";
        }
        // Execute an SQL query and store the result in the $result variable
        $result = $mysqli->query($sql);
        // Checks whether there are rows that match the query. Displaying the result in a table
        if ($result->num_rows > 0) { 
            echo "<table>";  
            // Outputting a table row containing column headers (<th>)
            echo "<tr><th style='width: 10%;'>Käyttäjä</th><th style='width: 8%;'>Laji</th><th style='width: 12%;'>Aika ja Yksikkö</th><th style='width: 12%;'>Päivämäärä ja kello</th><th style='width: 20%;'>Kommentti</th><th style='width: 19%;'>Toiminnot</th></tr>";
                // In a loop we print lines that satisfy the request
                while($row = $result->fetch_assoc()) { 
                    echo "<tr>"; 
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['laji_kuvaus'] . "</td>";
                    // Combining information from Aikamaara and Aikayksikko columns
                    $combinedInfo = $row['aikamaara'] . " " . $row['yksikko_kuvaus'];
                        // If Aikayksikko is in "askelta", convert to minutes, rounded to two decimal places
                        if ($row['yksikko_kuvaus'] == 'askelta') {
                            $askeltaValue = number_format($row['aikamaara'] / 130, 2); 
                            $combinedInfo .= " / " . $askeltaValue . " minuuttia";
                        }        
                    echo "<td>" . $combinedInfo . "</td>";
                    // Output date and time in a specific format
                    $date = date_create($row['pvmklo']);
                    echo "<td>" . date_format($date, "d.m.Y H:i:s") . "</td>";            
                    echo "<td>" . $row['kommentti'] . "</td>";
                    // Links to change or delete a specific sports workout
                    echo "<td><div class='buttons'><a href='muokkaa.php?id=" . $row['id_suoritus'] . "'><button>Muokkaa</button></a><a href='poista.php?id=" . $row['id_suoritus'] . "'><button>Poista</button></a></div></td>";
                    echo "</tr>"; 
                }
            echo "</table>";   
        } else {
            echo "Urheiluharjoittelua ei löytynyt";
        }

        // Additional options for viewing sports training statistics for the administrator
        if ($is_admin) {
            // Sum of all user workouts
            echo "<div class='centered-buttons'>";
            echo "<form action='statistics.php' method='get' class='buttons' style='margin-top: 10px;'>";
            echo "<button type='submit' style='width: 370px;'>Käyttäjien yhteenlasketut määrät</button>";
            echo "</form>";

            // Training statistics for the selected user
            echo "<form action='user_statistics.php' method='get' class='buttons' style='margin-top: 10px;'>";
            echo "<button type='submit' style='width: 370px;'>Tilastot minkä tahansa käyttäjän harjoituksista</button>";
            echo "</form>";
            echo "</div>";
        }
        $mysqli->close(); 
    ?>

    <!--View the total training duration for each sport-->
    <form action="total_statistics.php">
        <div class="buttons" style="margin-top: 10px;">
            <button type="submit" style="width: 250px;">Yhteenveto tiedot lajeittain</button>
        </div>
    </form>

    <!--Return to previous page-->
    <form action="luoda_poista_muuttaa.php">
        <div class="buttons" style="margin-top: 10px;">
            <button type="submit">Takaisin</button>
        </div>
    </form>  
</body>
</html>
