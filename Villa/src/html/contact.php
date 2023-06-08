<?php
$host = 'localhost:3306';
$dbUsername = "JanDeMan";
$dbPassword = "Settlover11";
$dbName = "Villa_DB";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['submitted'])) {

    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $telefoonnummer = $_POST['telefoonummer'];
    $email = $_POST['email'];
    $vraag = $_POST['vraag'];

    $stmt = $conn->prepare("INSERT INTO contact (naam, adres, telefoonnummer, email, vraag) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $naam, $adres, $telefoonnummer, $email, $vraag);
    $stmt->execute();

    $stmt->close();

    $_POST['submitted'] = true;

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contact.css">
    <title>Contact</title>
</head>
<body>
<div class="container">

    <div class="header">
        <div class="header1"></div>
        <a href="../../index.php"><div id="hom"><p>Home</p></div></a>
        <a href="contact.php"><div id="con"><p>Contact</p></div></a>
    </div>
    <div class="header2">
        <a href="#log in"><div id="log"><p>Log in</p></div></a>
        <a href="#register"><div id="reg"><p>Register</p></div></a>
    </div>
</div>

<div class="main">
    <h1>Neem contact met ons op</h1>
    <h2>Neem eenvoudig contact met ons op via onze contactpagina en ontvang snel antwoord op al je vragen over de adembenemende villa's die te koop staan op onze website.</h2>
</div>

<div class="submain">
    <h2>Contactformulier</h2>
    <div class="form-tekst">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="submain-left">
                <div class="form-vul">
                    <p>Naam:</p>
                </div>
                <div class="form-vul">
                    <p>Adres:</p>
                </div>
                <div class="form-vul">
                    <p>Telefoonummer:</p>
                </div>
                <div class="form-vul">
                    <p>Email:</p>
                </div>
                <div class="form-vul">
                    <p>Vraag:</p>
                </div>
            </div>

            <div class="submain-right">
                <div class="form-vul">
                    <input class="tekst"  type="text" name="naam" required>
                </div>
                <div class="form-vul">
                    <input class="tekst"  type="text" name="adres" required>
                </div>
                <div class="form-vul">
                    <input class="tekst"  type="number" name="telefoonummer" required>
                </div>
                <div class="form-vul">
                    <input class="tekst" type="text" name="email" required>
                </div>
                <div class="form-vul">
                    <textarea class="vraag" type="text" name="vraag" required></textarea>
                </div>
                <div class="form-vul-verzend">
                    <button type="submit">Verzend</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="footer">
    <div class="footer2">
        <a href="#mobile app"><div id="gon"><p>Mobile app</p></div></a>
        <a href="#community"><div id="gon"><p>Community</p></div></a>
        <a href="#company"><div id="gon"><p>Company</p></div></a>


        <img src="../img/logo.png" id="img2">


        <a href="#help desk"><div id="gon"><p>Help desk</p></div></a>
        <a href="#blog"><div id="gon"><p>Blog</p></div></a>
        <a href="#resources"><div id="gon"><p>Resources</p></div></a>
    </div>
</div>

</div>

</div>

</body>
</html>
