<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH MEAL HISTORY */
$historyQuery = "SELECT id, meal_name, calories, created_at
                 FROM meals
                 WHERE user_id='$user_id'
                 ORDER BY created_at DESC";;
$historyResult = mysqli_query($conn, $historyQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Meals | Fitness Buddy</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div class="meals-wrapper">

  <h1 class="page-title">Meals 🍽️</h1>

  <select id="mealType" class="meal-select">
    <option value="food">Solid Food</option>
    <option value="drink">Juice / Drink</option>
  </select>

  <div class="meals-grid">

    <!-- LEFT : INPUT + RESULT -->
    <div class="meal-card big">

      <input
        type="text"
        id="mealName"
        placeholder="Enter meal / drink name"><br><br>

      <textarea
        id="mealDesc"
        placeholder="Describe quantity ">
      </textarea><br>

      <button class="primary-btn" onclick="calculateMeal()">
        Calculate Calories
      </button><br>

      <!-- RESULT -->
      <div id="result" class="calorie-box">
        Calories will appear here
      </div><br>

      <a href="dashboard.php" class="back-btn">
        ← Back to Dashboard
      </a>

    </div>
    


    <!-- RIGHT : HISTORY -->
    <div class="meal-history big">
  <h3>📋 Meal Records</h3>
  <?php if ($historyResult && mysqli_num_rows($historyResult) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($historyResult)): ?>
      <div class="history-item">
        <div>
          <b><?php echo htmlspecialchars($row['meal_name']); ?></b>
          <small>
            <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
          </small>
        </div>

        <div class="history-actions">
          <span><?php echo $row['calories']; ?> kcal</span>

          <button onclick="editMeal(<?php echo (int)$row['id']; ?>)">✏️</button>
          <button onclick="deleteMeal(<?php echo (int)$row['id']; ?>)">🗑️</button>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="muted">No meals recorded yet</p>
    <small>Start by adding your first meal</small>
  <?php endif; ?>
</div>



  </div>
</div>

<script src="meal.js"></script>
</body>
</html>
