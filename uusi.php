<?php
// Check if the session is activated before calling session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../user/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <style>
        body {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        form {
            width: 400px; 
            margin: 0 auto;
            text-align: left;
        }

        label {
            display: block;
            text-align: center; 
            margin-top: 10px;
        }

        input[type="text"],
        textarea,
        input[type="datetime-local"],
        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .buttons {
            display: flex;
            justify-content: center; 
            margin-top: 10px;
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

    <form action="tallenna.php" method="post" enctype="multipart/form-data">

        <!-- Input field for comments -->
        <label for="kommentti">Kommentti:</label>
        <textarea id="kommentti" name="kommentti" rows="5" cols="60"></textarea>

        <!-- Input field for date and time -->
        <label for="pvmklo">Päivämäärä ja kellonaika:</label>
        <input type="datetime-local" id="pvmklo" name="pvmklo">

        <!-- Select field for choosing the type of sport -->
        <label for="laji_kuvaus">Laji:</label>
        <select id="laji_kuvaus" name="laji_kuvaus">
            <option value="" selected disabled>Valitse laji</option>
            <?php
            // Connect to the database
            $mysqli = new mysqli("localhost", "root", "", "liikuntasuorituksia"); 
            $mysqli->set_charset("utf8mb4");
            if ($mysqli->connect_error) {
                die("Virhe yhteyden muodostamisessa tietokantaan: " . $mysqli->connect_error);
            }

            // SQL query to get unique values for laji_kuvaus
            $sqlLaji = "SELECT DISTINCT laji_kuvaus FROM laji";
            $resultLaji = $mysqli->query($sqlLaji);

            // Output options for choosing the type of sport
            if ($resultLaji->num_rows > 0) {
                while ($row = $resultLaji->fetch_assoc()) {
                    echo "<option value='" . $row['laji_kuvaus'] . "'>" . $row['laji_kuvaus'] . "</option>";
                }
            } else {
                echo "<option value=''>Urheilutietoa ei ole saatavilla</option>";
            }
            ?>
        </select>

        <!-- Select field for choosing the unit of measurement -->
        <label for="yksikko_kuvaus">Yksikko:</label>
        <select id="yksikko_kuvaus" name="yksikko_kuvaus">
            <option value="" selected disabled>Valitse yksikko</option>
            <?php

            // SQL query to get unique values for yksikko_kuvaus
            $sqlYksikko = "SELECT DISTINCT yksikko_kuvaus FROM aikayksikko";
            $resultYksikko = $mysqli->query($sqlYksikko);

            // Output options for choosing the unit of measurement
            if ($resultYksikko->num_rows > 0) {
                while ($row = $resultYksikko->fetch_assoc()) {
                    echo "<option value='" . $row['yksikko_kuvaus'] . "'>" . $row['yksikko_kuvaus'] . "</option>";
                }
            } else {
                echo "<option value=''>Yksikkötietoja ei ole saatavilla</option>";
            }
            $mysqli->close();
            ?>
        </select>

        <!-- Input field for entering the quantity -->
        <label for="aikamaara">Määrä:</label>
        <input type="number" id="aikamaara" name="aikamaara" required>

        <!-- Button to submit the form -->
        <div class="buttons">
            <button type="submit">Lähetä</button>
        </div>
    </form>

</body>
</html>
