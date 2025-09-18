<?php
/**
* This file contains the NavigationMember Class Template
* 
*/

/**
 * 
 * NavigationMember class is a model class that implements the content generation for the
 * page navigation bar for a logged in Member user.  
 * 
 * To use this TEMPLATE - change 'Member' to the required usertype everywhere it appears 
 * 
 * eg: if you want to define a user type 'SUPPLIER'
 * Rename this file - replace the 'Member' with 'SUPPLIER' in the file name 
 * Then edit this file to REPLACE ALL 'Member' in this file with 'SUPPLIER' 
 * Move this file to its correct folder in the project eg /models/NavigationBarContent/ 
 * Finally include this file in the index.php 
 * 
 * @author Gerry Guinane
 * 
 */


class NavigationMember implements NavigationInterface {
    
        /**
         *
         * @var boolean $loggedin User logged in state 
         */
        protected $loggedin; 

        /**
         *
         * @var String $modelType Identifues this navigation model type  
         */
        protected $modelType; 

        /**
         *
         * @var String $pageID The currently selected page
         */
        protected $pageID;   

        /**
         *
         * @var array $menuNav An array of menu items  
         */
        protected $menuNav;    

        /**
         *
         * @var User $user  The current user object. 
         */
        protected $user;     

        
	/**
         * Class constructor. 
         * 
         * @param User $user The current user object.
         * @param string $pageID The currently selected page
         */
	function __construct($user,$pageID) {               
            $this->loggedin=$user->getLoggedInState();
            $this->modelType='NavigationMember';
            $this->user=$user;
            $this->pageID=$pageID;
            $this->setmenuNav();
	}

        /**
         * Method to prepare the navigation menu depending on the currently selected page ID. 
         * 
         * This method implements handlers for each page ID.  It prepares a HTML list item string
         * containing the menu items that will appear in the view. This string may be returned using the 
         * getMenuNav() method of this class.
         * 
         * If a user is not properly logged in it force redirects to the website home page. 
         * 
         */
        public function setmenuNav(){//set the menu items depending on the users selected page ID
            
            //empty string for menu items
            $this->menuNav='';

            $dropdownMenuMyAccount='<li class="dropdown">';
            $dropdownMenuMyAccount.='<a class="dropdown-toggle" data-toggle="dropdown" href="#">My Account<span class="caret"></span></a>';
            $dropdownMenuMyAccount.='<ul class="dropdown-menu">';
            $dropdownMenuMyAccount.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=myAccount">Uploads</a></li>';
            $dropdownMenuMyAccount.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=MemberUploadFile">Upload File</a></li>';
            $dropdownMenuMyAccount.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=MemberEditFiles">Edit Files</a></li>';
            $dropdownMenuMyAccount.='</ul></li>';



            if($this->loggedin){ 
                //handlers for logged in user
                switch ($this->pageID) {
                    //home navigation
                    case "home":
                        $this->menuNav.="$dropdownMenuMyAccount"; 
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=MemberSearchFiles">Search</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    
                    //menu item handlers
                    case "myAccount":
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuMyAccount"; 
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=MemberSearchFiles">Search</a></li>';
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=logout">Log Out</a></li>';
                        break;
        
                    case "MemberUploadFile":
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuMyAccount"; 
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=MemberSearchFiles">Search</a></li>';
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=logout">Log Out</a></li>';
                        break;

                     case "MemberEditFiles":
                            $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=home">Home</a></li>';
                            $this->menuNav.="$dropdownMenuMyAccount"; 
                            $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=MemberSearchFiles">Search</a></li>';
                            $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=logout">Log Out</a></li>';
                            break;
                    
                    case "MemberSearchFiles":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.="$dropdownMenuMyAccount"; 
                        $this->menuNav .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?pageID=MemberSearchFiles">Search</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;                    
                    
                    case "logout":
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem1">MenuItem1</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem2">MenuItem2</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                         
                   
                    default:
                        //$this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=home">Home</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem1">MenuItem1</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=menuItem2">MenuItem2</a></li>';
                        $this->menuNav.='<li><a href="'.$_SERVER['PHP_SELF'].'?pageID=logout">Log Out</a></li>';
                        break;
                    }//end switch                
            }
            else{
                //redirect to main index.php page
                header("Location:". $_SERVER['PHP_SELF']);
            }        
        } 

        /**
         * Getter to return the HTML menu string. 
         * 
         * @return string Containing  a HTML list item string containing the menu items that will appear in the view.
         */        
        public function getMenuNav(){return $this->menuNav;}    

        /**
         * Dumps diagnostic information in HTML format relating to the class properties
         */        
        public function getDiagnosticInfo(){

            echo '<!-- NAVIGATION ADMIN CLASS PROPERTY SECTION  -->';
                echo '<div class="container-fluid"   style="background-color: #AAAAAA">'; //outer DIV
                    
                    echo '<h3>NAVIGATION ADMIN (CLASS) properties</h3>';
                    echo '<table border=1 border-style: dashed; style="background-color: #EEEEEE" >';
                    echo '<tr><th>PROPERTY</th><th>VALUE</th></tr>';                        
                    echo "<tr><td>pageID</td>   <td>$this->pageID</td></tr>";
                    echo "<tr><td>menuNav</td>  <td>$this->menuNav      </td></tr>";
                    echo '</table>';
                    echo '<p><hr>';
                echo '</div>';            
            echo '<!-- END NAVIGATION CLASS PROPERTY SECTION  -->';
            
 }      

 
}
        