<?php

namespace Repository;
USE Manager\EntityRepository;
USE PDO;

class SalleRepository extends EntityRepository{
    
    public function checkForDoubles($userData = array()){
            $titre = $this->findByKey('titre', $userData);
            $query = $this->getDb()->prepare("SELECT titre FROM ".$this->getTableName()." WHERE titre='$titre'");
            $query->execute();
            $result = $query->rowCount();
            if(!$query){
                return false;
            }
            else{
                return $result;
            } 
        }
        
     public function addSalle(\Entity\Salle $salle){
         $query = $this->getDb()->prepare("INSERT INTO salle (pays, ville, adresse, cp, titre, description, photo, capacite) VALUES (:pays, :ville, :adresse, :cp, :titre, :description, :photo, :capacite)");
         $this->objectBinder($query,$salle);
         $result = $query->execute();
         return $result;
     }
     
     public function updateSalle($id, \Entity\Salle $salle){
         $query = $this->getDb()->prepare("UPDATE salle SET pays=:pays, ville=:ville, adresse=:adresse, cp=:cp, titre=:titre, description=:description, photo=:photo, capacite=:capacite WHERE id_salle='$id'");
         $this->objectBinder($query,$salle);
         $result = $query->execute();
         return $result;
     }
     
     public function deleteSalle($id_salle){
         $query = $this->getDb()->prepare("DELETE FROM salle WHERE id_salle='$id_salle'");
         $query->execute();
     }
     
     public function findById($id){
        $query = $this->getDb()->prepare("SELECT * FROM salle WHERE id_salle='$id'");
        $query->setFetchMode(PDO::FETCH_CLASS, 'Entity\\'.ucfirst($this->getTableName()));
        $query->execute();
        $result = $query->fetch();
        //var_dump($result);
        if(!$query){
            return false;
        }
        else{
            return $result;
        } 
     }
     
/*
 * Stats query Most sold
 */
     public function mostSold(){
        $query = $this->getDb()->prepare("SELECT p.id_produit, s.titre, COUNT(s.titre) as compte FROM produit p INNER JOIN details_commande d ON d.id_produit=p.id_produit INNER JOIN salle s ON p.id_salle=s.id_salle GROUP BY p.id_salle ORDER BY compte DESC LIMIT 0,5;");
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        return $result = $query->fetchAll();
    }
     
/*
 * Bind Objets (salles)
 */
     

        public function objectBinder($query, \Entity\Salle $salle){
            $whiteList = array('id_salle', 'submit', 'photoActuelle');
            foreach($salle as $key=>$value){
                if(!in_array($key, $whiteList)){
                    $query->bindValue(":$key",$value);
                }
            }
   
        }
     
     
}

