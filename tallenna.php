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
        .buttons a {
            display: block;
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
        // Checking if the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieving data from a form
            $kommentti = $_POST["kommentti"];
            $pvmklo = $_POST["pvmklo"];
            $laji_kuvaus = $_POST["laji_kuvaus"];
            $yksikko_kuvaus = $_POST["yksikko_kuvaus"];
            $aikamaara = $_POST["aikamaara"];

    
            // Function to check and insert a value into a table
            function insertValue($mysqli, $value, $table) {
                if (!empty($value)) {
                    $sqlCheck = "SELECT {$table}_id FROM {$table} WHERE {$table}_kuvaus = ?";
                    $stmtCheck = $mysqli->prepare($sqlCheck);
                    $stmtCheck->bind_param("s", $value);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();

                    if ($resultCheck->num_rows > 0) {
                        // The value exists, we get its ID
                        $row = $resultCheck->fetch_assoc();
                        return $row["{$table}_id"];
                    } else {
                        // The value does not exist, insert it into the table
                        $sqlInsert = "INSERT INTO {$table} ({$table}_kuvaus) VALUES (?)";
                        $stmtInsert = $mysqli->prepare($sqlInsert);
                        $stmtInsert->bind_param("s", $value);

                        if ($stmtInsert->execute()) {
                            // Getting the ID of the value just inserted
                            return $stmtInsert->insert_id;
                        } else {
                            echo "Error adding new value for {$table}_kuvaus: " . $stmtInsert->error;
                            exit;
                        }
                    }
                } else {
                    return null;
                }
            }

            // Getting ID for laji_kuvaus 
            $lajiId = insertValue($mysqli, $laji_kuvaus, "laji");
            // Getting 'yksikko_id' from table 'aikayksikko' for selected 'yksikko_kuvaus'
            $sqlYksikko = "SELECT yksikko_id FROM aikayksikko WHERE yksikko_kuvaus = ?";
            $stmtYksikko = $mysqli->prepare($sqlYksikko);
            $stmtYksikko->bind_param("s", $yksikko_kuvaus);
            $stmtYksikko->execute();
            // Getting 'yksikko_id'
            $yksikkoId = $stmtYksikko->get_result()->fetch_assoc()['yksikko_id'];

            // Start or resume the session to access session variables.
            session_start();
            // Retrieve the value stored in the session variable with the key "id" and assign it to the variable $kukaid.
            $kukaid =  $_SESSION["id"] ;
            // Retrieve the value stored in the session variable with the key "username" and assign it to the variable $kuka.
            $kuka = $_SESSION["username"] ;
            // Display the username using the echo statement.
            echo "Kayttaja: " . $kuka . "<br>";
            // Inserting data into the events table
            $insertEvent = "INSERT INTO suoritus (laji, kayttaja, pvmklo, aikamaara, aikayksikko, kommentti) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsertEvent = $mysqli->prepare($insertEvent);
            $stmtInsertEvent->bind_param("ississ", $lajiId, $kukaid, $pvmklo, $aikamaara, $yksikkoId, $kommentti);

            if ($stmtInsertEvent->execute()) {
                echo "Merkintä lisätty onnistuneesti.";
            } else {
                echo "Virhe lisättäessä merkintää: " . $stmtInsertEvent->error;
            }
        $mysqli->close();
        }
    ?>
    <!--Return to previous page-->
    <form action="luoda_poista_muuttaa.php">
        <div class="buttons" style="margin-top: 10px;">
            <button type="submit">Takaisin</button>
        </div>
    </form>
</body>
</html>
