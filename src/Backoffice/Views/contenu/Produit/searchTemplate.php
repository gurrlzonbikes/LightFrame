<?php
echo "<p>Résultats de la recherche pour  le mois de ".getMonth($vars['mois'])." ".$vars['annee']." à ".ucfirst($vars['motCle'])." :</p>";
echo "<div id='galerie'>";
echo "<ul class='produit'>";
//var_dump($vars);
function getMonth($inteGer){
    $mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre","Novembre", "Décembre");
    foreach($mois as $key=>$value){
        if($inteGer == ($key+1)){
            return $value;
        }
    }
}
//var_dump($produit);
if(!empty($produit)){
    foreach($produit as $key=>$value){
        if($value instanceof Entity\Produit){
                echo "<li>";
                echo "<div class='details'>";
                echo "<h4><a href='?controller=ProduitController&action=displayProductDetail&id=".$value->id_produit."'>".$value->titre."</a></h4>";
                echo "<a href='?controller=ProduitController&action=displayProductDetail&id=".$value->id_produit."'><img src='".$value->photo."'></a><br/>";
                echo "<div class='flaticon-ville'>".$value->ville."</div>";
                echo "<div class='flaticon-calend'>Du ".date("d-m-Y", strtotime($value->date_arrivee))." au ".date("d-m-Y", strtotime($value->date_depart))."</div>";
                echo "<div class='flaticon-capa'>".$value->capacite." personnes</div>";
                echo "<div class='flaticon-euro'>".$value->prix." euros</div>";
                echo "</div>";
                echo "<div class='linksProd'>";
                echo "<span><a href='?controller=ProduitController&action=displayProductDetail&id=".$value->id_produit."'>&#10095; Voir la fiche détaillée</a>";
                if(isset($_SESSION['user'])){
                    echo "<a href='?controller=CommandeController&action=addToCart&id=".$value->id_produit."' class='bouton'>Ajouter au panier</a>";
                }
                else{
                    echo "<a href='?controller=MembreController&action=loginDisplay' class='flyLogin'>&#10095; Connectez-vous pour l'ajouter au panier</a>";
                    }
                echo "</div>";
                echo "</li>";
        }
    }
}

else{
    echo "<div class='notFound'>Désolé, aucun résultat sur ces critères!</div>";
}
?>
</ul>
</div>