<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ADD WORKOUT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['workout'];
    $duration = $_POST['duration'];
    $date = date('Y-m-d');

    $insert = "INSERT INTO workouts (user_id, workout_name, duration, workout_date)
               VALUES ('$user_id', '$name', '$duration', '$date')";
    mysqli_query($conn, $insert);
}

// FETCH TODAY WORKOUTS
$today = date("Y-m-d");

$query = "SELECT * FROM workouts 
          WHERE user_id='$user_id' 
          AND DATE(created_at)='$today'
          ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// COUNT COMPLETED
$countQuery = "SELECT COUNT(*) as total 
               FROM workouts 
               WHERE user_id='$user_id' AND workout_date='$today'";
$countResult = mysqli_query($conn, $countQuery);
$completed = mysqli_fetch_assoc($countResult)['total'] ?? 0;

$workoutQuery = "
SELECT 
  w.workout_name,
  w.duration,
  w.created_at,
  IFNULL(ROUND((wm.calories_per_hour / 60) * w.duration), 0) AS calories_burned
FROM workouts w
LEFT JOIN workout_master wm
  ON LOWER(TRIM(w.workout_name)) = LOWER(TRIM(wm.workout_name))
WHERE w.user_id = '$user_id'
ORDER BY w.created_at DESC
";

$workoutResult = mysqli_query($conn, $workoutQuery);
?>

<!DOCTYPE html>
<html>
<head>
<title>Workouts | Fitness Buddy</title>
<link rel="stylesheet" href="../assets/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
<h1>Workouts 🏋️</h1>
<p class="subtitle">Log your training sessions</p>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<div class="card">
<form method="POST">
    <label>Workout Name</label>
    <input type="text" name="workout" placeholder="Chest / Cardio" required>

    <label>Duration (minutes)</label>
    <input type="number" name="duration" required>

    <button type="submit">Add Workout</button>
</form>
</div>

<div class="card">
<h3>Today's Workouts</h3>

<?php if ($workoutResult && mysqli_num_rows($workoutResult) > 0): ?>

  <?php while ($row = mysqli_fetch_assoc($workoutResult)): ?>

    <div class="workout-item">
      <b><?php echo htmlspecialchars($row['workout_name'] ?? 'Unknown'); ?></b>

      <small>
        <?php echo $row['duration'] ?? 0; ?> min
      </small>

      <span>
        <?php echo $row['calories_burned'] ?? 0; ?> kcal burned
      </span>
    </div>

  <?php endwhile; ?>

<?php else: ?>
  <p class="muted">No workouts recorded yet</p>
<?php endif; ?>
</div>
<h3>Completed: 💪 <?php echo $completed; ?></h3>
</div>

</div>
</body>
</html>
