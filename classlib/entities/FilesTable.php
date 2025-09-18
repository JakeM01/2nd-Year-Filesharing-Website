<?php
/**
* This file contains the FilesTable Class Template
* 
*/

/**
 * 
 * FilesTable entity class implements the table entity class for the 'FilesTable' table in the database. 
 * 
 * 
 * To use this TEMPLATE - change 'Files' to the required tablename everywhere it appears 
 * 
 * eg: if you want to define a table  'SUPPLIER'
 * Rename this file - replace the 'Files' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'Files' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /classlib/entities/ 
 * Finally include this file in the index.php 
 * 
 * 
 * 
 * @author Gerry Guinane
 * 
 */

class FilesTable extends TableEntity {

    /**
     * Constructor for the FilesTable Class
     * 
     * @param MySQLi $databaseConnection  The database connection object. 
     */
    function __construct($databaseConnection){
        parent::__construct($databaseConnection,'files');  //the name of the table is passed to the parent constructor
    }
    //END METHOD: Construct
   
    public function uploadFile($user, $postArray)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
            $fTypes = ['jpg', 'mp4', 'mp3'];
            
            $targetDir = "C:/xampp/htdocs/k00296742/FRAMEWORK_16_FILESHARE/uploads/";
            $userId = $user->getUserID();
            
        
            $fileName = basename($_FILES['fileToUpload']['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
           
            $targetFile = $targetDir . $fileName;
            $uploadFlag = 1;
            $filePermissions = $postArray['file_permissions'] ?? 'Private';
                
            if (!in_array($fileType, $fTypes)) {
                echo "Please upload file type of .jpg, .mp4 or .mp3";
                $uploadFlag = 0;
            }

            if (file_exists($targetFile)) {
                echo "File already exists.";
                $uploadFlag = 0;
            }

            if ($uploadFlag) {
                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
                    try {
                        
                        $stmt = $this->db->prepare("INSERT INTO files (file_name, file_URL, file_type, file_permissions, user_ID) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $fileName, $target_file, $fileType, $filePermissions, $userId);
                        $stmt->execute();
                        $stmt->close();
                        echo "File uploaded";
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                } else {
                    echo "There was an error uploading your file.";
                }
            }
        }
    }

    public function getFiles($userId)
    {
        
        $this->SQL = "SELECT file_name, file_type, user_ID, file_permissions FROM files WHERE user_ID = ?";
        
        
        try {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("s", $userId);
            $stmt->execute();
            $rs = $stmt->get_result();
            if ($rs->num_rows) {
                return $rs; 
            } else {
                return false; 
            }
        } catch (Exception $e) { 
            echo "Error: " . $e->getMessage();
        }
    }

    public function searchedFiles($fileName)
    {
        $this->SQL = "SELECT file_name, file_type, user_ID FROM files WHERE file_name LIKE ? AND file_permissions = 'Public'";
        
        try {
            $stmt = $this->db->prepare($this->SQL);
            $searchedFile = '%' . $fileName . '%';
            $stmt->bind_param("s", $searchedFile);
            $stmt->execute();
            $rs = $stmt->get_result();
            if ($rs->num_rows) {
                return $rs; 
            } else {
                return false; 
            }
        } catch (Exception $e) { 
            echo "Error: " . $e->getMessage();
        }
    }

    

    public function downloadFile($fileName) {
        $filePath = 'C:/xampp/htdocs/k00296742/FRAMEWORK_16_FILESHARE/uploads/' . $fileName;

        if (file_exists($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo 'File not found.';
        }
    }

    public function setFilePermissions($fileName, $filePermissions, $userId)
    {
            $this->SQL = "SELECT user_ID FROM files WHERE file_name = ?";
            try {
                $stmt = $this->db->prepare($this->SQL);
                $stmt->bind_param("s", $fileName);
                $stmt->execute();
                $stmt->bind_result($uploaderId);
                $stmt->fetch();
                $stmt->close();

                if ($uploaderId != $userId) {
                    echo "You do not have permission to change this file's permissions.";
                    return;
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                return;
            }


            $this->SQL = "UPDATE files SET file_permissions = ? WHERE file_name = ?";

            try {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("ss", $filePermissions, $fileName);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "File permissions updated";
            } else {
                echo "No file found";
            }
            $stmt->close();
            } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            }
    }

    public function deleteFiles($fileName, $userId)
    {
        $filePath = 'C:/xampp/htdocs/k00296742/FRAMEWORK_16_FILESHARE/uploads/' . $fileName;

        $this->SQL = "SELECT user_ID FROM files WHERE file_name = ?";  
        try{
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
            $stmt->bind_result($uploaderId);
            $stmt->fetch();
            $stmt->close();

            if ($uploaderId != $userId) {
                echo "You do not have permission to delete this file.";
                return;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            echo "File not found";
            return;
        }

        
        $this->SQL = "DELETE FROM files WHERE file_name = ?";

        try {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "File deleted";
            } else {
                echo "No file entry found in database.";
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function adminViewFiles()
    {
        $this->SQL="SELECT * FROM files";
        try{
            $rs=$this->db->query($this->SQL);
              if($rs->num_rows > 0){ 
                    return $rs; 
                }
                else{
                    return false;  
                }  
            
        } catch (mysqli_sql_exception $e) {
            return false;
        }
        
        
    }

    public function adminDeleteFiles($fileName)
    {
        $filePath = 'C:/xampp/htdocs/k00296742/FRAMEWORK_16_FILESHARE/uploads/' . $fileName;

        
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            echo "File not found";
            return;
        }

        
        $this->SQL = "DELETE FROM files WHERE file_name = ?";

        try {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("s", $fileName);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "File deleted";
            } else {
                echo "No file entry found in database.";
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function adminSetFilePermissions($fileName, $filePermissions)
    {
        
            $this->SQL = "UPDATE files SET file_permissions = ? WHERE file_name = ?";

            try {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("ss", $filePermissions, $fileName);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "File permissions updated";
            } else {
                echo "No file found";
            }
            $stmt->close();
            } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            }
    }
        
}
