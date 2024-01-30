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
        // Checking if the session is activated before calling session_start()
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Checking if the user is logged in
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: ../user/login.php");
        exit;
        }
        // Connecting to the database
        $mysqli = new mysqli("localhost", "root", "", "liikuntasuorituksia"); 
        $mysqli->set_charset("utf8mb4");
            if ($mysqli->connect_error) {
                die("Virhe yhteyden muodostamisessa tietokantaan: " . $mysqli->connect_error);
            }
            // Check if the 'id' parameter is set in the URL ($_GET).
            if (isset($_GET['id'])) {
                // Convert the 'id' parameter to an integer to ensure it is a valid numeric value.
                $id_suoritus = (int)$_GET['id'];
    
                // SQL query to delete information by specified ID
                $deleteQuery = "DELETE FROM suoritus WHERE id_suoritus = $id_suoritus";

                if ($mysqli->query($deleteQuery) === TRUE) {
                    echo "Tietue poistettu onnistuneesti.";
                } else {
                echo "Virhe poistettaessa tietuetta: " . $mysqli->error;
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
