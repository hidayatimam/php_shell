<!DOCTYPE html>
<html>

<head>
  <title>File Manager</title>
  <style>
    body {
      background-color: black;
      color: white;
      font-family: 'Courier New', monospace;
      margin: 0;
      padding: 20px;
    }

    h2 {
      color: #2778ff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid white;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #2778ff;
      color: black;
    }

    a {
      color: #4eff4e;
      /* Green color for links */
    }
  </style>
</head>

<body>
  <h2>File Manager</h2>

  <?php
  function listFiles($directory)
  {
    $files = array_diff(scandir($directory), array('.', '..'));

    echo '<table>';
    echo '<tr><th>Name</th><th>Type</th><th>Actions</th></tr>';

    foreach ($files as $file) {
      $filePath = $directory . '/' . $file;
      $fileType = is_dir($filePath) ? 'Directory' : 'File';

      echo "<tr>";
      echo "<td>$file</td>";
      echo "<td>$fileType</td>";
      echo "<td><a href='file_manager.php?action=view&file=$file'>View</a> | ";
      echo "<a href='file_manager.php?action=download&file=$file'>Download</a> | ";
      echo "<a href='file_manager.php?action=delete&file=$file' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";
      echo "</tr>";
    }

    echo '</table>';
  }

  function viewFile($file)
  {
    $filePath = "./$file";
    if (file_exists($filePath) && is_file($filePath)) {
      $fileContent = htmlentities(file_get_contents($filePath));
      echo "<h2>Viewing $file</h2>";
      echo "<pre>$fileContent</pre>";
    } else {
      echo "<h2>File not found: $file</h2>";
    }
  }

  function downloadFile($file)
  {
    $filePath = "./$file";
    if (file_exists($filePath) && is_file($filePath)) {
      header('Content-Type: application/octet-stream');
      header("Content-Disposition: attachment; filename=$file");
      readfile($filePath);
      exit;
    } else {
      echo "<h2>File not found: $file</h2>";
    }
  }

  function deleteFile($file)
  {
    $filePath = "./$file";
    if (file_exists($filePath)) {
      unlink($filePath);
      echo "<h2>File deleted: $file</h2>";
    } else {
      echo "<h2>File not found: $file</h2>";
    }
  }

  $action = isset($_GET['action']) ? $_GET['action'] : '';
  $file = isset($_GET['file']) ? $_GET['file'] : '';

  switch ($action) {
    case 'view':
      viewFile($file);
      break;
    case 'download':
      downloadFile($file);
      break;
    case 'delete':
      deleteFile($file);
      listFiles('.'); // After deletion, refresh the file list
      break;
    default:
      listFiles('.');
      break;
  }
  ?>
</body>

</html>
