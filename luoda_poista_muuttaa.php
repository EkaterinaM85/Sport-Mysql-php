<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            margin: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="button-container">
        <form action="listaa.php" method="post"> <!--link to view, edit or delete already saved sports workouts-->
            <button type="submit">Tarkastele, muokkaa tai poista urheiluharjoituksia</button> 
        </form>

        <form action="uusi.php" method="post"> <!--link to create a new sports workout-->
            <button type="submit">Luoda uusi urheiluharjoituksia</button> 
        </form>
    </div>
</body>
</html>
