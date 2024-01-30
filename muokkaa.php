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
            max-width: 800px; 
        }
        input[type="text"],
        textarea,
        input[type="datetime-local"] {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .button-container {
            margin-top: 15px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 5px;
            box-sizing: border-box;
            margin-top: 10px;
        }
        select:focus {
            outline: none;
            border-color: #007bff;
        }
        .custom-button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: calc(100% - 20px); 
            margin-top: 10px;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-top: 10px;
        }     
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

$id_suoritus = $_GET['id'];

// Validation and sanitization of user input to prevent SQL injections
$id_suoritus = (int)$id_suoritus;

$sql = "SELECT * FROM kaikkitiedot WHERE id_suoritus=$id_suoritus";
// Logic for updating data in the database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $laji_kuvaus = $_POST['laji_kuvaus'];
    $pvmklo = $_POST['pvmklo'];
    $aikamaara = $_POST['aikamaara'];
    $yksikko_kuvaus = $_POST['yksikko_kuvaus'];
    $kommentti = $_POST['kommentti'];
    
    // Getting the corresponding yksikko_id based on the selected yksikko_kuvaus
$yksikko_kuvausQuery = "SELECT yksikko_id FROM aikayksikko WHERE yksikko_kuvaus = '$yksikko_kuvaus'";
$yksikkoResult = $mysqli->query($yksikko_kuvausQuery);
if ($yksikkoResult->num_rows > 0) {
    $yksikkoRow = $yksikkoResult->fetch_assoc();
    $yksikko_id = $yksikkoRow['yksikko_id'];
}

// Getting the corresponding laji_id based on the selected laji_kuvaus, only if it's changed
if(isset($_POST['laji_kuvaus']) && $_POST['laji_kuvaus'] != $row['laji_kuvaus']) {
    $laji_kuvaus = $_POST['laji_kuvaus'];
    $lajiQuery = "SELECT laji_id FROM laji WHERE laji_kuvaus = '$laji_kuvaus'";
    $lajiResult = $mysqli->query($lajiQuery);

    if ($lajiResult->num_rows > 0) {
        $lajiRow = $lajiResult->fetch_assoc();
        $laji_id = $lajiRow['laji_id'];
    } else {
        // Если не найдено соответствие, установите $laji_id в NULL или другое значение по умолчанию
        $laji_id = NULL;
    }
} else {
    // Если не выбран новый laji_kuvaus, оставляем текущий laji_id
    $laji_id = $row['laji'];
}

// Getting the corresponding laji_id based on the selected laji_kuvaus
$lajiQuery = "SELECT laji_id FROM laji WHERE laji_kuvaus = '$laji_kuvaus'";
$lajiResult = $mysqli->query($lajiQuery);
if ($lajiResult->num_rows > 0) {
    $lajiRow = $lajiResult->fetch_assoc();
    $laji_id = $lajiRow['laji_id'];
}

// Updating data in a table kaikkitiedot
$updateKaikkitiedot = "UPDATE kaikkitiedot SET laji = '$laji_id', aikamaara = '$aikamaara', aikayksikko = '$yksikko_id', pvmklo = '$pvmklo', kommentti = '$kommentti' WHERE id_suoritus = $id_suoritus";
    if ($mysqli->query($updateKaikkitiedot) === TRUE) {
        echo "Kaikki tiedot on päivitetty onnistuneesti";
    } else {
        echo "Virhe päivitettäessä tietoja: " . $mysqli->error;
    }
}

$sql = "SELECT * FROM kaikkitiedot WHERE id_suoritus=$id_suoritus";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>

<!-- Data editing form -->
<form method="post" action="" enctype="multipart/form-data">
        <label for="kommentti">Kommentti:</label>
        <textarea id="kommentti" name="kommentti" rows="10" cols="65"><?php echo $row['kommentti']; ?></textarea>
        <label for="pvmklo">Päivämäärä ja kellonaika:</label>
        <input type="datetime-local" id="pvmklo" name="pvmklo" style="width: 100%;" value="<?php echo $row['pvmklo']; ?>">
        

        <label for="laji_kuvaus">Laji:</label>
<select id="laji_kuvaus" name="laji_kuvaus">
    <option value="" disabled>Valitse laji</option>
    <?php
          $sqlLaji = "SELECT DISTINCT laji_kuvaus FROM laji";
       $resultLaji = $mysqli->query($sqlLaji);   
       if ($resultLaji->num_rows > 0) {
           while ($row_laji = $resultLaji->fetch_assoc()) {
               $selected = ($row_laji['laji_kuvaus'] == $row['laji_kuvaus']) ? 'selected' : '';
               echo "<option value='" . $row_laji['laji_kuvaus'] . "' $selected>" . $row_laji['laji_kuvaus'] . "</option>";
           }
   } else {
       echo "<option value='' disabled>Ei lajitietoja saatavilla</option>";
   }
    ?>
</select>  
<label for="aikamaara">Aikamäärä:</label>
<input type="text" id="aikamaara" name="aikamaara" value="<?php echo $row['aikamaara']; ?>" required>
<label for="yksikko_kuvaus">Yksikkö:</label>
<select id="yksikko_kuvaus" name="yksikko_kuvaus">
    <option value="" disabled>Valitse yksikkö</option>
    <?php
    $sqlYksikko = "SELECT DISTINCT yksikko_kuvaus FROM aikayksikko";  
    $resultYksikko = $mysqli->query($sqlYksikko);

    if ($resultYksikko->num_rows > 0) {
        while ($row_yksikko = $resultYksikko->fetch_assoc()) {
            $selected = ($row_yksikko['yksikko_kuvaus'] == $row['yksikko_kuvaus']) ? 'selected' : '';
            echo "<option value='" . $row_yksikko['yksikko_kuvaus'] . "' $selected>" . $row_yksikko['yksikko_kuvaus'] . "</option>";
        }
    } else {
        echo "<option value='' disabled>Ei yksikkotietoja saatavilla</option>";
    }
    ?>
</select><br>     
        
    <div class="button-container">
        <input type="submit" value="Muokkaa">
    </div>
</form>

<?php
} else {
    echo "Ei tietoja muokattavaksi"; 
}
$mysqli->close();
?>
<!--Return to previous page-->
<form action="listaa.php">
    <div class="button-container">
        <input type="submit" value="Takaisin">
    </div>
</form>   
</body>
</html>
