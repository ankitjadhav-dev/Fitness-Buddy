<?php
session_start();
include "../config/db.php";   // ✅ ADD THIS

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$today = date('Y-m-d');

$waterQuery = "SELECT intake_ml, goal_ml 
               FROM water_intake 
               WHERE user_id='$user_id' AND intake_date='$today'";
$waterResult = mysqli_query($conn, $waterQuery);

$intake_ml = 0;
$goal_ml = 2000;

if ($waterResult && mysqli_num_rows($waterResult) > 0) {
    $row = mysqli_fetch_assoc($waterResult);
    $intake_ml = $row['intake_ml'];
    $goal_ml = $row['goal_ml'];
}

$waterPercent = min(100, ($intake_ml / $goal_ml) * 100);



// 🔽 STEP 1 CODE GOES HERE
$metricQuery = "SELECT height_cm, weight_kg, bmi 
                FROM body_metrics 
                WHERE user_id = '$user_id' 
                ORDER BY recorded_at DESC 
                LIMIT 1";

$metricResult = mysqli_query($conn, $metricQuery);
$metrics = null;

if ($metricResult && mysqli_num_rows($metricResult) > 0) {
    $metrics = mysqli_fetch_assoc($metricResult);
}

$height = $metrics['height_cm'] ?? '--';
$weight = $metrics['weight_kg'] ?? '--';
$bmi    = $metrics['bmi'] ?? '--';

$today = date('Y-m-d');
$calQuery = "SELECT SUM(calories) as total 
             FROM meals 
             WHERE user_id='$user_id' AND meal_date='$today'";
$calResult = mysqli_query($conn, $calQuery);
$caloriesToday = mysqli_fetch_assoc($calResult)['total'] ?? 0;

$proteinQuery = "SELECT SUM(protein) as total 
                 FROM meals 
                 WHERE user_id='$user_id' AND meal_date='$today'";
$proteinResult = mysqli_query($conn, $proteinQuery);
$proteinToday = mysqli_fetch_assoc($proteinResult)['total'] ?? 0;

$today = date('Y-m-d');
$workoutQuery = "SELECT COUNT(*) as total 
                 FROM workouts 
                 WHERE user_id='$user_id' AND workout_date='$today'";
$workoutResult = mysqli_query($conn, $workoutQuery);
$workoutsToday = mysqli_fetch_assoc($workoutResult)['total'] ?? 0;

$today = date('Y-m-d');

$burnQuery = "
SELECT 
  SUM((wm.calories_per_hour / 60) * w.duration) AS calories_burned
FROM workouts w
JOIN workout_master wm 
  ON LOWER(w.workout_name) = LOWER(wm.workout_name)
WHERE w.user_id = '$user_id'
AND w.workout_date = '$today'
AND w.status = 'completed'
";

$burnResult = mysqli_query($conn, $burnQuery);
$burnData = mysqli_fetch_assoc($burnResult);

$caloriesBurnedToday = round($burnData['calories_burned'] ?? 0);

$quotes = [
  "Don’t stop until you’re proud 💪",
  "Small steps every day lead to big results 🔥",
  "Consistency beats motivation 🧠",
  "Your body can stand almost anything. It’s your mind you have to convince.",
  "Discipline is choosing what you want most over what you want now.",
  "No excuses. Just results.",
  "Sweat today. Smile tomorrow 😌",
  "Progress, not perfection."
];

$dailyQuote = $quotes[array_rand($quotes)];



?>


<!DOCTYPE html>
<html>
<head>
  <title>Fitness Buddy | Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="app-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="hamburger" onclick="toggleSidebar()">☰</div>
      <img src="../assets/images/logo.png" alt="Fitness Buddy">
      <div class="mobile-menu-btn" onclick="toggleMenu()">☰</div>
      <span>Fitness Buddy</span>
    </div>

    <nav class="menu" id="mobileMenu">
      <a href="#" class="active">Dashboard</a>
      <a href="meals.php">Meals</a>
      <a href="workouts.php">Workouts</a>
      <a href="progress.php">Progress</a>
      <a href="../auth/logout.php" class="logout">Logout</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">
    <header class="dashboard-header">
      <div class="hamburger" onclick="toggleSidebar()">☰</div>
<header class="dashboard-header">
  <div>
    <h1>
  Welcome, 
  <?php 
    echo htmlspecialchars(
      explode(" ", $_SESSION['user_name'])[0]
    ); 
  ?> 💪
</h1>

    <p class="muted">Consistency beats motivation</p>
  </div>
  <div class="date-pill">
    <?php echo date("D, d M"); ?>
    
  </div>
  
</header>


    </header>
<div class="motivation-box">
  <span>💡 Daily Motivation</span>
  <p><?php echo $dailyQuote; ?></p>
</div>
    <section class="dashboard-cards">

  <div class="stat-card green">
    <div class="icon">🍽️</div>
    <div class="info">
      <h3>Calories Intake</h3>
      <p><?php echo $caloriesToday; ?> kcal</p>
    </div>
  </div>

  <div class="stat-card blue">
  <div class="icon">🥚</div>
  <div class="info">
    <h3>Protein Today</h3>
    <p><?php echo round($proteinToday,1); ?> g</p>
  </div>
</div>

  <div class="stat-card orange">
    <div class="icon">🏋️</div>
    <div class="info">
      <h3>Workouts</h3>
      <p><?php echo $workoutsToday; ?> completed</p>
    </div>
  </div>

  <div class="stat-card purple">
  <div class="icon">🔥</div>
  <div>
    <h3>Calories Burned</h3>
    <p><?php echo $caloriesBurnedToday; ?> kcal</p>
  </div>
</div>

  <div class="stat-card green">
    <div class="icon">⚖️</div>
    <div class="info">
      <h3>Weight</h3>
      <p><?php echo $weight; ?> kg</p>
    </div>
  </div>

  <div class="stat-card blue">
  <div class="icon">📏</div>
  <div class="info">
    <h3>Height</h3>
    <p><?php echo $height; ?> cm</p>
  </div>
</div>

<div class="stat-card orange">
  <div class="icon">📊</div>
  <div class="info">
    <h3>BMI</h3>
    <p><?php echo $bmi; ?></p>
  </div>
</div>

<div class="stat-card purple">
  <div class="icon">💧</div>
  <div class="info">
    <h3>Water Intake</h3>
    <p><?php echo $intake_ml; ?> / <?php echo $goal_ml; ?> ml</p>

    <div class="water-bar">
      <div class="water-fill" style="width: <?php echo $waterPercent; ?>%"></div>
    </div>

    <div class="water-actions">
      <button onclick="drinkWater()">+200 ml</button>
      <button onclick="revertWater()">↩️ Revert</button>
      <button onclick="openGoalModal()">⋮</button>
    </div>
  </div>
</div>





</section>

  </main>

</div>
<script>
function toggleMenu() {
  const menu = document.getElementById("mobileMenu");

  if (menu.style.display === "flex") {
    menu.style.display = "none";
  } else {
    menu.style.display = "flex";
    menu.style.flexDirection = "column";
  }
}
</script>
<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("show");
}
</script>
<script>
function drinkWater() {
  fetch("water.php", { method: "POST" })
    .then(res => res.text())
    .then(data => {
      if (data === "success") location.reload();
    });
}

function openGoalModal() {
  const goal = prompt("Set daily water goal (1000–8000 ml):");
  if (!goal) return;

  const goalValue = parseInt(goal);
  if (goalValue < 1000 || goalValue > 8000) {
    alert("Goal must be between 1000 ml and 8000 ml");
    return;
  }

  fetch("set_water_goal.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "goal=" + goalValue
  }).then(() => location.reload());
}

function revertWater() {
  fetch("water_revert.php", { method: "POST" })
    .then(() => location.reload());
}
</script>






</body>

</html>
