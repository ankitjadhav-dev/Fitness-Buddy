<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$bmi = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   $weight = $_POST['weight'];
   $height_cm = $_POST['height'];

   $height_m = $height_cm / 100;
   $bmi = round($weight / ($height_m * $height_m), 1);

   if ($bmi < 18.5) $status = "Underweight";
   elseif ($bmi < 25) $status = "Normal";
   elseif ($bmi < 30) $status = "Overweight";
   else $status = "Obese";

   // ✅ ADD THIS PART (SAVE TO DB)
   $insert = "INSERT INTO body_metrics (user_id, height_cm, weight_kg, bmi)
              VALUES ('$user_id', '$height_cm', '$weight', '$bmi')";
   mysqli_query($conn, $insert);
}
$chartQuery = "SELECT bmi, weight_kg, DATE(recorded_at) as date
               FROM body_metrics
               WHERE user_id = '$user_id'
               ORDER BY recorded_at ASC";

$chartResult = mysqli_query($conn, $chartQuery);

$bmiData = [];
$weightData = [];
$dateData = [];

while ($row = mysqli_fetch_assoc($chartResult)) {
    $bmiData[] = $row['bmi'];
    $weightData[] = $row['weight_kg'];
    $dateData[] = $row['date'];
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Progress | Fitness Buddy</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="../assets/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="main-content">
<h1>Progress 📈</h1>
<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<p class="subtitle">Track your body metrics</p><br><br>

<div class="card">
<form method="POST">
  <label>Height (cm)</label>
  <input type="number" name="height" required>

  <label>Weight (kg)</label>
  <input type="number" name="weight" required>

  <button type="submit">Calculate BMI</button>
</form>


<?php if ($bmi): ?>
    <h3>Your BMI: <?php echo $bmi; ?></h3>
    <p>Status: <b><?php echo $status; ?></b></p>
<?php endif; ?>
</div>

</div>

<!-- Progress Chart Card -->
<div class="card">
    <h3>BMI & Weight Progress</h3>
    <canvas id="progressChart" height="120"></canvas>
</div>

<script>
const ctx = document.getElementById('progressChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dateData); ?>,
        datasets: [
            {
                label: 'BMI',
                data: <?php echo json_encode($bmiData); ?>,
                borderColor: '#39ff14',
                tension: 0.4
            },
            {
                label: 'Weight (kg)',
                data: <?php echo json_encode($weightData); ?>,
                borderColor: '#00bcd4',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: 'white' } }
        },
        scales: {
            x: { ticks: { color: 'white' } },
            y: { ticks: { color: 'white' } }
        }
    }
});
</script>


</body>
</html>
