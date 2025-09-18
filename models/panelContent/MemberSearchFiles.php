<?php
/**
* This file contains the MemberSearchFilesPanelContent Class Template
* 
*/


/**
 * MemberSearchFilesPanelContent is an extended PanelModel Class
 * 
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for an <em><b>MemberSearchFiles user type home</b></em> page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * To use this TEMPLATE - change 'MemberSearchFiles' to the required usertype everywhere it appears 
 * 
 * eg: if you want to define a user type 'SUPPLIER'
 * Rename this file - replace the 'MemberSearchFiles' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'MemberSearchFiles' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /models/panelContent/ 
 * Finally include this file in the index.php 
 * 
 * @author gerry.guinane
 * 
 */


class MemberSearchFiles extends PanelModel{
    

    
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
        $this->modelType='MemberSearchFiles';
        parent::__construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID);
    } 


    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1(){
            $this->panelHead_1='<h3>Search Files</h3>';
    }
     /**
     * Set the Panel 1 text content 
     */  
    public function setPanelContent_1(){
        require_once 'forms/Form.php';
        $this->panelContent_1 .= Form::form_search_File($this->pageID);
    }      

    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2(){ 
        $this->panelHead_2='<h3>Results</h3>';
    }
    
     /**
     * Set the Panel 2 text content 
     */    
    public function setPanelContent_2(){
        if (isset($this->postArray['btnSearch'])) {
            $fileName = $this->postArray['fileName'];
            $filesTable = new FilesTable($this->db);
            $rs = $filesTable->searchedFiles($fileName);

            if($rs)
            {
                $this->panelContent_2= HelperHTML::generateTABLE($rs);
                $this->panelContent_2 .= Form::form_download_File($this->pageID, $fileName);
            }
            else
            {
                $this->panelContent_2='<p>No files found</p>';
            }
        }

        if (isset($this->postArray['btnDownload'])) {
            $fileName = $this->postArray['fileName'];
            $filesTable = new FilesTable($this->db);
            $filesTable->downloadFile($fileName);
        }
    }  

    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3(){ 
        $this->panelHead_3='<h3>Comments</h3>'; 
    } 
    
    
     /**
     * Set the Panel 3 text content 
     */ 
    public function setPanelContent_3(){ 
        $fileName = null;
        if (isset($this->postArray['btnSearch'])) {
            $fileName = $this->postArray['fileName'];
        }

        if (isset($this->postArray['btnAddComment'])) {
            $fileName = $this->postArray['file_name']; 
            $comment_text = $this->postArray['comment_text'];
            $rating = $this->postArray['rating'];
            $commentsTable = new CommentsTable($this->db);
            $commentsTable->insertComments($this->user->getUserID(), $fileName, $comment_text, $rating);
        }

        if ($fileName) {
            $commentsTable = new CommentsTable($this->db);
            $rs = $commentsTable->getComments($fileName);
    
            if ($rs) {
                $this->panelContent_3 = HelperHTML::generateTABLE($rs);
            } else {
                $this->panelContent_3 = '<p>No comments found</p>';
            }
            $this->panelContent_3 .= Form::form_add_comment($this->pageID, $fileName);
            }         
    }         
}
        