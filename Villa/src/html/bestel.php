<?php
$host = 'localhost:3306';
$dbUsername = "JanDeMan";
$dbPassword = "Settlover11";
$dbName = "Villa_DB";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $voornaam = $_POST["voornaam"];
    $achternaam = $_POST["achternaam"];
    $bod = $_POST["bod"];
    $email = $_POST["email"];

    $date = date("y-m-d");

    $insertQuery = "INSERT INTO biedingen$pageNumber (voornaam, achternaam, bod, email, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sssss", $voornaam, $achternaam, $bod, $email, $date);

    if ($stmt->execute()) {

        header("Location: ".$_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }

    $stmt->close();
}

$query = "SELECT id, titel, beschrijving, adres, image FROM villas WHERE id = ?";
$stmt = $conn->prepare($query);

$stmt->bind_param("i", $pageNumber);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
    $id = $row['id'];
    $titel = $row['titel'];
    $beschrijving = $row['beschrijving'];
    $adres = $row['adres'];
    $image = $row['image'];
} else {
    echo "No results found.";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bestel.css">
    <title>Bestel</title>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header1"></div>
        <a href="../../index.php"><div id="hom"><p>Home</p></div></a>
        <a href="contact.php"><div id="con"><p>Contact</p></div></a>

        <div class="header2">
            <a href="#log in"><div id="log"><p>Log in</p></div></a>
            <a href="#register"><div id="reg"><p>Register</p></div></a>
        </div>
    </div>

    <div class="main">
        <img src="<?php echo $image; ?>">
    </div>

    <p id="p-main"><?php echo $titel; ?></p>
    <div class="submain">
        <div class="left-main">
            <div class="indeling">
                <h2>Indeling</h2>
                <p><?php echo $beschrijving; ?></p>
            </div>
            <div class="locatie">
                <h2>Locatie</h2>
                <iframe src="https://www.google.com/maps/embed?pb=<?php echo $adres; ?>" class="google"></iframe>
            </div>
        </div>

        <div class="right-main">
            <div>
                <h2>Hoogste Biedingen</h2>
                <div class="bieding">
                    <div class="bod-naam">
                        <?php
                        $topOffersQuery = "SELECT voornaam, achternaam, bod, date FROM biedingen$pageNumber ORDER BY bod DESC LIMIT 3";
                        $topOffersResult = $conn->query($topOffersQuery);

                        if ($topOffersResult && $topOffersResult->num_rows > 0) {
                            while ($offerRow = $topOffersResult->fetch_assoc()) {
                                $voornaam = $offerRow['voornaam'];
                                $achternaam = $offerRow['achternaam'];
                                $bod = $offerRow['bod'];
                                $date = $offerRow['date'];

                                echo "<p>$voornaam $achternaam</p>";
                            }
                        } else {
                            echo "Geen biedingen gevonden.";
                        }
                        ?>
                    </div>
                    <div class="bod-prijs">
                        <?php
                        $topOffersResult = $conn->query($topOffersQuery);

                        if ($topOffersResult && $topOffersResult->num_rows > 0) {
                            while ($offerRow = $topOffersResult->fetch_assoc()) {
                                $bod = $offerRow['bod'];
                                $date = $offerRow['date']; // Fetch the date value from the database

                                echo "<p>€ $bod | $date</p>";
                            }
                        } else {
                            echo "";
                        }
                        ?>
                    </div>
                </div>
                <?php
                $totalBidsQuery = "SELECT COUNT(*) AS total_bids FROM biedingen$pageNumber";
                $totalBidsResult = $conn->query($totalBidsQuery);

                if ($totalBidsResult && $totalBidsResult->num_rows > 0) {
                    $totalBidsRow = $totalBidsResult->fetch_assoc();
                    $totalBids = $totalBidsRow['total_bids'];

                    echo "<p>Aantal biedingen: $totalBids</p>";
                } else {
                    echo "<p>Aantal biedingen: 0</p>";
                }
                ?>
            </div>
            <div>
                <h2>Doe een bod</h2>
                <div class="bod">
                    <div class="form-tekst">
                        <?php

                        $highestBidQuery = "SELECT MAX(bod) AS highest_bid FROM biedingen$pageNumber";
                        $highestBidResult = $conn->query($highestBidQuery);

                        if ($highestBidResult && $highestBidResult->num_rows > 0) {
                            $highestBidRow = $highestBidResult->fetch_assoc();
                            $highestBid = $highestBidRow['highest_bid'];

                        } else {
                            $highestBid = 0;
                        }

                        $minimumBid = max(1000000, $highestBid + 1); 

                        ?>
                        <form method="POST" action="">
                            <div class="form-vul">
                                <p>Email:</p>
                                <input class="invul"  type="text" name="email" required>
                            </div>
                            <div class="form-vul">
                                <p>Voornaam:</p>
                                <input class="invul"  type="text" name="voornaam" required>
                            </div>
                            <div class="form-vul">
                                <p>Achternaam:</p>
                                <input class="invul"  type="text" name="achternaam" required>
                            </div>
                            <div class="form-vul">
                                <p>Bod:</p>
                                <input class="invul-bod" type="number" name="bod" required min="<?php echo $minimumBid; ?>">
                            </div>
                            <div class="form-vul-button">
                                <button type="submit">Verzend</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

<script src="../js/index.js"></script>

</body>
</html>