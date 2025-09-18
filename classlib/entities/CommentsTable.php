<?php
/**
* This file contains the XXXTable Class Template
* 
*/

/**
 * 
 * XXXTable entity class implements the table entity class for the 'XXXTable' table in the database. 
 * 
 * 
 * To use this TEMPLATE - change 'XXX' to the required tablename everywhere it appears 
 * 
 * eg: if you want to define a table  'SUPPLIER'
 * Rename this file - replace the 'XXX' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'XXX' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /classlib/entities/ 
 * Finally include this file in the index.php 
 * 
 * 
 * 
 * @author Gerry Guinane
 * 
 */

class CommentsTable extends TableEntity {

    /**
     * Constructor for the XXXTable Class
     * 
     * @param MySQLi $databaseConnection  The database connection object. 
     */
    function __construct($databaseConnection){
        parent::__construct($databaseConnection,'comments');  //the name of the table is passed to the parent constructor
    }
    //END METHOD: Construct

    public function getComments($filename)
    {
        $this->SQL = "SELECT comment_text, rating, user_id FROM comments WHERE file_name = ?";  
        try
        {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("s", $filename);
            $stmt->execute();
            $rs = $stmt->get_result();
            if ($rs->num_rows) {
                return $rs; 
            } else {
                return false; 
            }
        }catch(Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }

    public function insertComments($UserId, $filename, $comment_text, $rating)
    {
        $this->SQL = "INSERT INTO comments (comment_text, rating, file_name, user_ID) VALUES (?, ?, ?, ?)";
        try
        {
            $stmt = $this->db->prepare($this->SQL);
            $stmt->bind_param("siss", $comment_text, $rating, $filename, $UserId);
            if ($stmt->execute()) {
                return true; 
            } else {
                return false; 
            }
        }catch(Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
    }
}

