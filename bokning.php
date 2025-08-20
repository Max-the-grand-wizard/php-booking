<?php
// booking.php - Här hanterar vi bokningar och avbokningar

// Om användaren har skickat bokningsformuläret
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tid']) && isset($_POST['namn'])) {
    $tid = $_POST['tid']; // Den valda tiden för bokning
    $namn = $_POST['namn']; // Namnet på personen som bokar

    // Förbered SQL-frågan för att uppdatera bokningen
    $stmt = $db->prepare("UPDATE tidsslottar SET bokad_av = ? WHERE tid = ?");
    $stmt->bind_param("ss", $namn, $tid); // Binda parametrarna till frågan

    // Utför frågan och ge feedback till användaren
    if ($stmt->execute()) {
        echo "Bokningen gjordes för $namn på $tid.<br>"; // Meddelande vid lyckad bokning
    } else {
        echo "Fel vid bokning: " . $stmt->error . "<br>"; // Felmeddelande om bokningen misslyckas
    }
    $stmt->close(); // Stäng prepared statement
}

// Om användaren har skickat avbokningsformuläret
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['avboka_tid'])) {
    $avboka_tid = $_POST['avboka_tid']; // Tiden som ska avbokas

    // Förbered SQL-frågan för att avboka bokningen
    $stmt = $db->prepare("UPDATE tidsslottar SET bokad_av = NULL WHERE tid = ?");
    $stmt->bind_param("s", $avboka_tid); // Binda parametern

    // Utför frågan och ge feedback
    if ($stmt->execute()) {
        echo "Bokningen för tiden $avboka_tid har avbokats.<br>"; // Meddelande vid lyckad avbokning
    } else {
        echo "Fel vid avbokning: " . $stmt->error . "<br>"; // Felmeddelande om avbokningen misslyckas
    }
    $stmt->close(); // Stäng prepared statement
}
?>





