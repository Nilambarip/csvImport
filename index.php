<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use csvImport\DataSource;
use csvImport\csvImportService;

require_once 'DataSource.php';
require_once 'service/csvImportService.php';
$db = new DataSource();
$connection = $db->getConnection();
$csvImportService = new csvImportService();

if (isset($_POST["import"])) {
    $response = $csvImportService->importCSV($_FILES);
}
?>
<!DOCTYPE html>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="public/main.css">
    <script src="public/main.js"></script>
    <title>CSV IMPORT</title>
</head>

<body>
<div class="container">
    <div class="outer-container">
        <div class="row">
            <div class="input-row">
                <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                    <h3> Import CSV file into Mysql Database</h3>
                    <?php
                         $type = $csvImportService->getType();
                         $message = $csvImportService->getMessage();
                    ?>
                    <div id="response" class="<?php echo empty($type)?: $type . " display-block" ?>">
                        <?php echo empty($message)?: $message ?>
                    </div>
                    <fieldset>
                        <label class="col-md-4 control-label">Choose CSV File</label>
                        <input type="file" name="file"  id="file" accept=".csv">
                    </fieldset>
                    <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
                    <br/>

                    <?php
                    $sqlSelect = "SELECT * FROM users";
                    $result = $db->select($sqlSelect);
                    if (!empty($result)) {
                    ?>
                        <table id='userTable'>
                            <thead>
                            <tr>
                                <th>User Name</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                            </tr>
                            </thead>
                            <?php foreach ($result as $row) { ?>
                            <tbody>
                            <tr>
                                <td><?php echo $row['userName']; ?></td>
                                <td><?php echo $row['firstName']; ?></td>
                                <td><?php echo $row['lastName']; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

</html>