<?php
include 'db.php';

$sql = "SELECT * FROM games";
$result = $conn->query($sql);

$games = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $games[] = $row;
    }
} else {
    echo "0 resultados";
}
$conn->close();
?>
