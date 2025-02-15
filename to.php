<?php

$file = 'tasks.json';

if (file_exists($file)) {
  $tasks = json_decode(file_get_contents($file), true);
  if (!is_array($tasks)) {
    $tasks = [];
  }
} else {
  $tasks = [];
}

$edit_task = ""; 
$edit_id = "";   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['contant'])) {
    $task_desc = trim($_POST['contant']);
    if (!empty($task_desc)) {
      if (!empty($_POST['edit_id'])) {
        foreach ($tasks as &$task) {
          if ($task['id'] == $_POST['edit_id']) {
            $task['task'] = $task_desc;
            break;
          }
        }
      } else {
        $new_task = [
          'id' => uniqid(),
          'task' => $task_desc ];
        array_unshift($tasks, $new_task);
      }

      file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
      header("Location: to.php");
      exit;
    }
  }

  if (isset($_POST['Delete'])) {
    $delete_id = $_POST['Delete'];
    foreach ($tasks as $key => $task) {
      if ($task['id'] == $delete_id) {
        unset($tasks[$key]);
        break;
      }
    }
    $tasks = array_values($tasks); 
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: to.php");
    exit;
  }

  if (isset($_POST['Edit'])) {
    $edit_id = $_POST['Edit'];
    foreach ($tasks as $task) {
      if ($task['id'] == $edit_id) {
        $edit_task = $task['task'];
        break;
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>To-Do List</title>
  <style>
body {
  background-color: #f7ede2;
  display: flex;
  justify-content: center;
  height: 97vh;
  font-family: Arial, sans-serif;
}

main {
  display: flex;
  justify-content: space-around;
  flex-direction: column;
  height: 95vh;
  align-items: center;
}

.continar {
  margin-top: 50px;
  background-color: #ff8c42;
  height: 70px;
  padding: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 15px;
  width: 400px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

button,
#complete {
  background-color: #2a9d8f; /* لون أخضر */
  color: white;
  font-weight: bold;
  padding: 10px 15px;
  border-radius: 20px;
  border: none;
  cursor: pointer;
  transition: background 0.3s ease;
}

#complete:hover {
  background-color: #21867a; /* لون أغمق عند تمرير الماوس */
}

button:hover,
#complete:hover {
  background-color: #e76f51;
}

#text {
  border: 1px solid #ccc;
  padding: 10px;
  border-radius: 5px;
  width: 280px;
  outline: none;
}

.form {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 400px;
}

.tasks {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.task {
  display: flex;
  width: 400px;
  align-items: center;
  justify-content: space-between;
  background-color: #eaeaea;
  padding: 15px 20px;
  border-radius: 10px;
  box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
}

.article {
  font-size: 16px;
  font-weight: bold;
  color: #333;
}

.button {
  display: flex;
  gap: 8px;
}

button[name="Delete"] {
  background-color: #e63946;
}

button[name="Delete"]:hover {
  background-color: #d62839;
}

button[name="Edit"] {
  background-color: #457b9d;
}

button[name="Edit"]:hover {
  background-color: #1d3557;
}

  </style>
</head>

<body>
  <main>
    <div class="continar">
      <form action="" method="post" class="form">
        <input type="text" name="contant" value="<?= $edit_task ?>" id="text" />
        <input type="hidden" name="edit_id" value="<?= $edit_id ?>">
        <input type="submit" value="Add Task" id="complete" />
      </form>
    </div>

    <div class="tasks">
      <?php if ($tasks): ?>
        <?php foreach ($tasks as $task): ?>
          <div class="task">
            <div class="article"><?= $task['task']; ?></div>
            <div class="button">
              <form method="POST" action="">
                <button type="submit" name="Delete" value="<?= $task['id'] ?>">Delete</button>
                <button type="submit" name="Edit" value="<?= $task['id'] ?>">Edit</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>
</body>

</html>