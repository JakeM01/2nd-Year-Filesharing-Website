<?php
/**
* This file contains the MemberEditFiles Class Template
* 
*/


/**
 * MemberEditFiles is an extended PanelModel Class
 * 
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for an <em><b>Member user type home</b></em> page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * To use this TEMPLATE - change 'Member' to the required usertype everywhere it appears 
 * 
 * eg: if you want to define a user type 'SUPPLIER'
 * Rename this file - replace the 'Member' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'Member' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /models/panelContent/ 
 * Finally include this file in the index.php 
 * 
 * @author gerry.guinane
 * 
 */


class MemberEditFiles extends PanelModel{
    

    
    /**
    * Constructor Method
    * 
    * The constructor for the PanelModel class. This class provides the 
    * panel content for up to 3 page panels.
    * 
    * @param User $user  The current user
    * @param MySQLi $db The database connection handle
    * @param Array $postArray Copy of the $_POST array
    * @param String $pageTitle The page Title
    * @param String $pageHead The Page Heading
    * @param String $pageID The currently selected Page ID
    */  
    function __construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID){  
        $this->modelType='memberEditFiles';
        parent::__construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID);
    } 


    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1(){
            $this->panelHead_1='<h3>Set File Permissions</h3>';
    }
     /**
     * Set the Panel 1 text content 
     */  
    public function setPanelContent_1(){
        require_once 'forms/Form.php';
        $pageID=$this->pageID;
        $this->panelContent_1 .= Form::form_set_permissions($pageID);
        $filesTable = new FilesTable($this->db);
        if (isset($this->postArray['btnSet'])) {
            $fileName = $this->postArray['file_name'];
            $filePermissions = $this->postArray['file_permissions'];
            $userId = $this->user->getUserId();
            $this->panelContent_1 .= $filesTable->setFilePermissions($fileName, $filePermissions, $userId);
        }
    }      


    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2(){ 
        $this->panelHead_2='<h3>Delete Files</h3>';
    }
    
     /**
     * Set the Panel 2 text content 
     */    
    public function setPanelContent_2(){
        require_once 'forms/Form.php';
        $pageID=$this->pageID;
        $this->panelContent_2.= Form::form_delete_File($pageID);
        $filesTable = new FilesTable($this->db);
        if (isset($this->postArray['btnDelete'])) {
            $fileName = $this->postArray['file_name'];
            $userId = $this->user->getUserId();
            $this->panelContent_2 .= $filesTable->deleteFiles($fileName, $userId);
        }
    }  

    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3(){ 
        $this->panelHead_3='<h3>Application Setup</h3>'; 
    } 
    
    
     /**
     * Set the Panel 3 text content 
     */ 
    public function setPanelContent_3(){ 
            $this->panelContent_3='<p>To set up this application read the following <a href="readme/installation.php" target=”_blank” >SETUP INSTRUCTIONS</a></p>';            
    }         



     

}
        