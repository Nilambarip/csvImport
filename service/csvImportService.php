<?php
namespace csvImport;
class csvImportService{

    public $connection;
    public $db;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $message;

    public function __construct()
    {
        $this->db = new DataSource();
        $this->connection = $this->db->getConnection();
    }

    /**
     * @param array $files
     * @return string[]
     */
    final public function importCSV( array $files)
    {
        $fileName = $files["file"]["tmp_name"];

        if ($files["file"]["size"] > 0) {

            $file = fopen($fileName, 'rb');

            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

                $userId = "";
                if (isset($column[0])) {
                    $userId = mysqli_real_escape_string($this->connection, $column[0]);
                }
                $userName = "";
                if (isset($column[1])) {
                    $userName = mysqli_real_escape_string($this->connection, $column[1]);
                }
                $password = "";
                if (isset($column[2])) {
                    $password = mysqli_real_escape_string($this->connection, $column[2]);
                }
                $firstName = "";
                if (isset($column[3])) {
                    $firstName = mysqli_real_escape_string($this->connection, $column[3]);
                }
                $lastName = "";
                if (isset($column[4])) {
                    $lastName = mysqli_real_escape_string($this->connection, $column[4]);
                }

                $sqlInsert = "INSERT into users (userId,userName,password,firstName,lastName) VALUES (?,?,?,?,?)";
                $paramType = "issss";
                $paramArray = array(
                    $userId,
                    $userName,
                    $password,
                    $firstName,
                    $lastName
                );
                $insertId = $this->db->insert($sqlInsert, $paramType, $paramArray);

                if (!empty($insertId)) {
                    $this->type = "success";
                    $this->message = "CSV Data Imported into the Database";
                } else {
                    $this->type = "error";
                    $this->message = "Problem in Importing CSV Data";
                }
            }
        }
    }
    public  function  getType()
    {
        return $this->type;
    }
    public  function  getMessage()
    {
        return $this->message;
    }
}