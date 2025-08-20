<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bokningssystem</title>
    <link rel="stylesheet" href="styles.css"> <!-- Länk till CSS-fil -->
</head>
<body>

<!-- Navigationsmeny -->
<header>
    <nav>
        <ul class="nav-links">
            <li><a href="#">Hem</a></li>
            <li><a href="#">Bokningar</a></li>
            <li><a href="#">Tjänster</a></li>
            <li><a href="#">Om Oss</a></li>
            <li><a href="#">Kontakt</a></li>
           
        </ul>
    </nav>
</header>


<section class="hero">
    <div class="hero-content">
        <h1>Välkommen till vårt Bokningssystem</h1>
        <p>Enkel och snabb hantering av dina bokningar.</p>
    </div>
</section>


<div class="container">
    <h2>Boka eller avboka en tid</h2>

    <?php
    // Anslut till databasen
    include 'db.php';

    // POST-förfrågning för bokning
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['tid']) && isset($_POST['namn'])) {
            // Boka tid
            $tid = mysqli_real_escape_string($db, $_POST['tid']);
            $namn = mysqli_real_escape_string($db, $_POST['namn']);

            // Uppdatera databasen för att sätta bokad_av
            $bokning_query = "UPDATE tidsslottar SET bokad_av='$namn' WHERE tid='$tid' AND bokad_av IS NULL";
            if (mysqli_query($db, $bokning_query)) {
              //  echo "<p class='success'>Tiden $tid har bokats av $namn!</p>";
            } else {
                echo "<p class='error'>Fel vid bokning: " . mysqli_error($db) . "</p>";
            }
        }

        // Hantera POST-request för avbokning
        if (isset($_POST['avboka_tid'])) {
            // Avboka tid
            $tid = mysqli_real_escape_string($db, $_POST['avboka_tid']);

            // Uppdatera databasen för att ta bort bokad_av
            $avbokning_query = "UPDATE tidsslottar SET bokad_av=NULL WHERE tid='$tid'";
            if (mysqli_query($db, $avbokning_query)) {
               // echo "<p class='success'>Tiden $tid har avbokats!</p>";
            } else {
                echo "<p class='error'>Fel vid avbokning: " . mysqli_error($db) . "</p>";
            }
        }
    }
    ?>

 <!-- boka tid -->
<form method="POST">
    <label for="tid">Välj tid:</label>
    <select name="tid" id="tid" required>
        <?php
        // Hämta tillgängliga tider med prepared statements
        $time_query = $db->prepare("SELECT tid FROM tidsslottar WHERE bokad_av IS NULL");
        $time_query->execute();
        $time_result = $time_query->get_result();

        // Om det finns tillgängliga tider
        if ($time_result && $time_result->num_rows > 0) {
            while ($time_row = $time_result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($time_row['tid']) . "'>" . htmlspecialchars($time_row['tid']) . "</option>";
            }
        } else {
            echo "<option disabled>Inga tillgängliga tider</option>";
        }
        ?>
    </select>


    <label for="namn">Ditt namn:</label>
<input type="text" name="namn" id="namn" required maxlength="50" pattern="[A-Za-zÅÄÖåäö\s]+" title="Endast bokstäver och mellanslag tillåts">

    <input type="submit" value="Boka">
</form>

<!-- Formulär för att avboka en tid -->
<form method="POST">
    <label for="avboka_tid">Avboka tid:</label>
    <select name="avboka_tid" id="avboka_tid" required>
        <?php
        // Hämta bokade tider med prepared statements
        $booked_time_query = $db->prepare("SELECT tid FROM tidsslottar WHERE bokad_av IS NOT NULL");
        $booked_time_query->execute();
        $booked_time_result = $booked_time_query->get_result();

        // Om det finns bokade tider
        if ($booked_time_result && $booked_time_result->num_rows > 0) {
            while ($booked_time_row = $booked_time_result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($booked_time_row['tid']) . "'>" . htmlspecialchars($booked_time_row['tid']) . "</option>";
            }
        } else {
            echo "<option disabled>Inga bokade tider</option>";
        }
        ?>
    </select>

    <input type="submit" value="Avboka">
</form>

    <h2>Tillgängliga tider och bokningar</h2>

    <?php
    // Hämta alla bokningstider från databasen
    $query = "SELECT * FROM tidsslottar";
    $result = mysqli_query($db, $query);

    // Kontrollera om frågan lyckades
if ($result) {
    echo "<div class='time-slots'>"; // Starta div för att hålla alla tider
    while ($row = mysqli_fetch_assoc($result)) {
        $tid = htmlspecialchars($row['tid']);
        $bokad_av = $row['bokad_av'] ? htmlspecialchars($row['bokad_av']) : "Ej bokad";
        $bokad_class = $row['bokad_av'] ? "booked" : "available"; // Klass baserat på om tiden är bokad eller ej

        // Visa tid och bokad_av i snygga kort
        echo "<div class='slot-card $bokad_class'>
                <span class='time-icon'>&#128337;</span> <!-- En ikon som representerar tid -->
                <p>Tid: $tid</p>
                <p>Bokad av: $bokad_av</p>
              </div>";
    }
    echo "</div>"; // Stäng div för tider
} else {
    // Om något gick fel med SQL-frågan, visa ett felmeddelande
    echo "<div class='error-message'>Fel vid hämtning av data: " . mysqli_error($db) . "</div>";
}


    // Stäng databasanslutningen
    mysqli_close($db);
    ?>
</div>


<footer>
    <div class="footer-content">
        <p>&copy; Bokningsrätten</p>
        <ul class="footer-links">
            <li><a href="#">Integritetspolicy</a></li>
            <li><a href="#">Användarvillkor</a></li>
            <li><a href="#">Kontakta Oss</a></li>
        </ul>
    </div>
</footer>



</body>
</html>
