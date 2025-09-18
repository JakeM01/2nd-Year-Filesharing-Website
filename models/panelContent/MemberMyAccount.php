<?php
/**
* This file contains the XXXPanelContent Class Template
* 
*/


/**
 * XXXPanelContent is an extended PanelModel Class
 * 
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for an <em><b>XXX user type home</b></em> page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * To use this TEMPLATE - change 'XXX' to the required usertype everywhere it appears 
 * 
 * eg: if you want to define a user type 'SUPPLIER'
 * Rename this file - replace the 'XXX' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'XXX' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /models/panelContent/ 
 * Finally include this file in the index.php 
 * 
 * @author gerry.guinane
 * 
 */


class MemberMyAccount extends PanelModel{
    

    
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
        $this->modelType='MemberMyAccount';
        parent::__construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID);
    } 


    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1(){
            $this->panelHead_1='<h3>Uploads</h3>';
    }
     /**
     * Set the Panel 1 text content 
     */  
    public function setPanelContent_1(){
        $filesTable = new FilesTable($this->db);
        $userId = $this->user->getUserID();
        $rs = $filesTable->getFiles($userId);

        if($rs)
        {
            $this ->panelContent_1= HelperHTML::generateTABLE($rs);
        }
        else
        {
            $this->panelContent_1='<p>No files uploaded</p>';
        }

    }      

    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2(){ 

    }  

    /**
     * Set the Panel 2 text content 
     */
    public function setPanelContent_2(){
        
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
        