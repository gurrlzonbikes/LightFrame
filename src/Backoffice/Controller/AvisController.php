<?php
namespace Backoffice\Controller;
USE Controller\Controller;

class AvisController extends Controller{
    
/*
 * Display for public
 */
    public function findBySalle($id){
        $query = $this->getRepository('Avis');
        $result = $query->findBySalleId($id);
        return $result;
    }
    
/*
 * List for admin
 * 
 */
    
    public function listeForAdmin(){
        $result = $this->getRepository('Avis')->fetchAll();
        $this->view->displayForAdmin($result);
    }
    
/*
 * Insert
 * 
 */
    
    public function addFeedbk(){
        if(isset($this->arrayPost)){
        $this->verifFeedback();
        if(!empty($this->msg)){
            $prodCont = new ProduitController;
            $prodCont->displayProductDetail();
            echo $this->msg;
        }
        elseif(empty($this->msg)){
            $this->getRepository('Avis')->addFeedback($this->arrayPost);
            header("Location: ?controller=ProduitController&action=displayProductDetail&id=".$this->arrayGet['id']);
            exit();
        }
      }
    }
    
    public function verifFeedback(){
        $this->clean($this->arrayPost);
        $this->checkForEmptyFields($this->arrayPost);
    }
    
/*
 * Delete
 */
    
    public function flushAdmin(){
        $this->clean($this->arrayGet);
        $this->getRepository('Avis')->deleteFeedback($this->arrayGet['id']);
        $this->listeForAdmin();
    }

/*
 * Check si le membre a deja laissé un avis sur la salle
 */

    public function checkFeedbackLeft($user, $salle){
        $result = $this->getRepository('Avis')->checkDouble($user, $salle);
        if($result !==0){
            $this->msg='Merci de votre contribution!';
            return $this->msg;
        }
        
        else{
            return false;
        }
    }
    
/*
 * Stats
 */
    public function topVenuesAvg(){
        $result = $this->getRepository('Avis')->bestRanked();
        return $result;
    }
}