// meal.js

document.addEventListener("DOMContentLoaded", () => {

  const mealName = document.getElementById("mealName");
  const mealDesc = document.getElementById("mealDesc");
  const mealType = document.getElementById("mealType");
  const resultBox = document.getElementById("result");

  // ✅ MAKE FUNCTIONS GLOBAL
  window.calculateMeal = function () {
    const name = mealName.value.trim();
    const desc = mealDesc.value.trim();
    const type = mealType.value;

    if (!name || !desc) {
      resultBox.innerHTML = "❌ Please enter meal name and description";
      return;
    }

    resultBox.innerHTML = "⏳ Calculating...";

    fetch("meal_calculate.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `name=${encodeURIComponent(name)}&desc=${encodeURIComponent(desc)}&type=${type}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        resultBox.innerHTML = "❌ " + data.error;
        return;
      }

      resultBox.innerHTML = `
        🔥 <b>${data.calories} kcal</b><br>
        🥚 <b>${data.protein} g protein</b>
      `;

      mealName.value = "";
      mealDesc.value = "";
    })
    .catch(() => {
      resultBox.innerHTML = "❌ Server error";
    });
  };

  window.deleteMeal = function (id) {
    if (!confirm("Delete this meal?")) return;

    fetch("delete_meal.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "id=" + id
    })
    .then(() => location.reload());
  };

  window.editMeal = function (id) {
    const newDesc = prompt("Edit meal description:");
    if (!newDesc) return;

    fetch("edit_meal.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${id}&desc=${encodeURIComponent(newDesc)}`
    })
    .then(() => location.reload());
  };

});