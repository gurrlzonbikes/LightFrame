<?php
namespace Backoffice\Views;
USE Views\Views;

class DefaultViews extends Views{
    
    public function indexDisplay($salles, $msg=''){
        $this->render('template_accueil.php','defaultPlaceholder.php', array(
            'title'=>'Accueil',
            'salle'=>$salles,
            'msg'=>$msg
        ));
    }
    
    public function displayListe($args){
        echo "test";
    }
    
    public function displayFicheDetail($args) {
        echo "";
    }
    
    public function displayForAdmin($result){
        echo "";
    }
    
    public function displayMentionsLegales(){
        $this->render('template_accueil.php', 'mentionslegales.php', array(
            'title'=>'Mentions  Légales'
        ));
    }
    
    public function displayCgv(){
        $this->render('template_accueil.php', 'cgv.php', array(
            'title'=>'Conditions générales de vente'
        ));
    }
    
    public function displayStatsAdmin($bestRanked, $mostSold, $mostQty, $mostExp){
        $this->render('template_accueil.php', 'stats.php', array(
            'title'=>'Statistiques du site',
            'bestRanked'=>$bestRanked,
            'mostSold'=>$mostSold,
            'mostQty'=>$mostQty,
            'mostExp'=>$mostExp
        ));
    }
    
    public function displayAbout(){
        $this->render('template_accueil.php', 'aboutus.php', array(
            'title'=>'Qui sommes-nous?'
        ));
    }
    
    public function displayContactForm($msg){
        $this->render('template_accueil.php', 'contact.php', array(
            'title'=>'Nous contacter',
            'msg'=>$msg
        ));
    }
    
    public function noSuchPage(){
        $this->render('template_accueil.php', 'nopage.php', array(
            'title'=>'Oops... Où allez-vous?'
        ));
    }
    
    public function okContact(){
        $this->render('template_accueil.php', 'thankyou.php', array(
            'title'=>'Votre message a été envoyé'
        ));
    }
    
    public function displaySupport(){
        $this->render('template_accueil.php', 'support.php', array(
            'title'=>'Support'
        ));
    }
    
}

