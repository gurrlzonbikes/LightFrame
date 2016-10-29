<?php
namespace Backoffice\Controller;
USE Controller\Controller;
USE Component\UserSessionHandler;


class MembreController extends Controller{
    
/*
 * Fonctions de lancement
 */
    
   public function lanceSignUp(){
       if(isset($this->arrayPost)){
           $this->verifSignUp();
            if(is_null($this->msg[0])){
            $this->signUp();
            $this->getRepository('Membre')->loginQuery($this->arrayPost);
            header('Location: ?controller=DefaultController&action=indexDisplay');
            exit();
            }
            else{
                $this->view->justSignUp($this->msg);
                $this->msg = '';
            }
        }
    }
    
    public function insertAdmin(){
        if(isset($this->arrayPost)){
           $this->verifSignUp();
           $errors = array_filter($this->msg);
           //var_dump($this->msg);
            if(empty($errors)){
                $this->getRepository('Membre')->insertNewAdmin($this->arrayPost);
                $this->welcomeNewAdmin($this->arrayPost['email'], $this->arrayPost['pseudo'], $this->arrayPost['mdp']);
                header('Location:?controller=MembreController&action=adminOk');
                exit();
            }
            else{
                $this->view->signUpAdmin($this->msg);
            }
        }
    }
    
    public function verifSignUp(){
            $this->clean($this->arrayPost);
            $testDoubles = $this->getRepository('Membre')->checkForDoubles($this->arrayPost);
            if($testDoubles == false){
                $this->msg = $this->checkForEmptyFields($this->arrayPost);
                $this->msg[] =  $this->checkLength();
          }
            else{
            return $this->msg = "Pseudo/Email deja pris!<br/>Avez-vous <a href='#'>oublié votre mot de passe?</a>";
          } 
    }
    
    public function checkLength(){
        $longueurPseudo = strlen($this->arrayPost['pseudo']);
        $longueurMdp = strlen($this->arrayPost['mdp']);
        if($longueurMdp < 3 || $longueurPseudo < 3){
            return "Votre pseudo et votre mot de passe doivent faire au minimum 3 charactères";
        }
    }
    
    public function lanceLogin(){
       if($this->isPostSet()!=false){
            $this->loginUser($this->arrayPost);
            \Component\CookieBakery::atLogin($this->arrayPost);
            //var_dump($this->msg);
            if(empty($this->msg)){
                $default = new DefaultController;
                $default->indexDisplay('');
            }
            else{
                $this->view->justLogin($this->msg);
            }
    }
 }
    
/*
 * Login : on nettoie, on lance la query, on fait l'erreur
 * propre si besoin
 * @params array($dataInput) tous les $_POST
 * @return object self:$user instance de la Classe Membre
 */
    public function loginUser($dataInput){
        $myObj = $this->formValidation('loginQuery', $dataInput);
        if($myObj == false){
            $this->msg = "Mauvaise combinaison de login/mot de passe";
        }
        else{
            $this->userSession->initializeSession($myObj);
         }
         //var_dump($this->msg);
         return $this->msg;
    }
    
    public function formValidation($fonction1, $data=array()){
        $this->clean($data);
        $this->msg = $this->checkForEmptyFields($data);
        if(empty($this->msg)){
            $table = explode('\\', get_called_class());
            $requestTable = str_replace('Controller','',$table[2]);
            $queryTable = $this->getRepository($requestTable);
            $result = $queryTable->$fonction1($data);
            return $result;
        }
    }
        
    
/*
 * Fonction d'inscription
 * @params array($dataInput) tous les $_POST
 * @return object $this->user instance de la Classe Membre
 */

    public function signUp(){
            $this->allowInsert($this->arrayPost);
    }
    
    public function allowInsert($data = array()){
        $queryTable = $this->getRepository('Membre');
        $myResult = $queryTable->signUpQuery($data);
        if($myResult == false){
            echo "perdu!";
          }
        else{
            $myObject = $queryTable->loginQuery($this->arrayPost);
            if($myObject == false){
                $this->msg = "Nous avons eu un problème au moment de la connexion";
                echo $this->msg;
            }
            else{
                $this->userSession->initializeSession($myObject);
            }
          }
    }

/*
 * Delete
 */
     public function allowDelete(){
             $queryTable = $this->getRepository('Membre');
             $queryTable->deleteMembre($this->arrayGet['id']);
             $this->displayForAdmin();
     }
     
/*
 * Update
 */
     
     public function updateUser(){
         if(isset($this->arrayPost)){
              $this->clean($this->arrayPost);
              $this->msg = $this->checkForEmptyFields($this->arrayPost);
              $this->msg[] =  $this->checkLength();             
              $errors = array_filter($this->msg);
              //var_dump($errors);
             if(empty($errors)){
                $this->getRepository('Membre')->updateMembre($this->arrayPost,$this->arrayGet['id']);
                $moi = $this->getRepository('Membre')->findById($this->arrayGet['id']);
                $this->userSession->initializeSession($moi);
                $this->displayFicheDetail();
             }
             else{
                 $me = UserSessionHandler::getUser();
                 $this->view->updateForm($me, $errors);
             }
         }
     }
     
/*
 * Send new password
 */
     public function mailPwd(){
         //var_dump($this->arrayPost);
         $this->clean($this->arrayPost);
         $emailExists = $this->getRepository('Membre')->checkIfEmailExists($this->arrayPost['email']);
            if($emailExists == false){
                $this->msg = "Pas d'utilisateur enregistré avec cette adresse!";
                $this->view->forgoPwd($this->msg);

            }
            else{
               $pwd = $this->randomPwd();
               $this->mailLostPwd($this->arrayPost['email'], $pwd);
               $this->getRepository('Membre')->updatePassword(md5($pwd), $emailExists['id_membre']);
               $this->view->lostPwd();
            }
     }
     
     
/*
 * Display
 */
     
     public function adminOk(){
         $this->view->newAdminOk();
     }
     
     public function justLogin(){
         $this->view->justLogin('');
     }
     
     public function justSignUp(){
         $this->view->justSignUp('');
     }
     
     public function updateProfil(){
        $me = UserSessionHandler::getUser();
        $this->view->updateForm($me, '');
     }
    

    
     public function signUpForm(){
        $this->view->signUpForm($this->msg);
    }
    
    public function signUpFormAdmin(){
        $this->view->signUpAdmin('');
    }
    
    public function loginDisplay(){
        $this->view->loginDisplay();
        
    }
    
    public function lostPwdForm(){
        $rien = '';
        $this->view->forgoPwd($rien);
    }
    
    public function displayFicheDetail(){
        $me = UserSessionHandler::getUser();
        $commCont = new CommandeController;
        $myOrders = $commCont->getMyOrders($me->id_membre);
        $this->view->displayFicheDetail($me, $myOrders);
    }
    public function displayForAdmin(){
        $query = $this->getRepository('Membre');
        $result = $query->findAll();
        $this->view->displayForAdmin($result);
    }
    
    public function deconnexion(){
        \session_destroy();
        unset($_SESSION['user']);
        header('Location: ?controller=DefaultController&action=indexDisplay');
        exit();
    }
    
    
}
