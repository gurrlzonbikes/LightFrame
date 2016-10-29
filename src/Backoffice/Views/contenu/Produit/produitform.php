<?php
if(isset($msg) && is_array($msg)){
    foreach($msg as $key=>$value){
        echo "<div class='error'>".$value."</div>";
    }
}

elseif(isset($msg) && !is_array($msg)){
    echo "<div class='error'>".$msg."</div>";
}
?>
<form method="post" action="?controller=ProduitController&action=lanceSaveProduct" class='siteForms'>
<label>Date d'arrivée </label>
<input type="text" class="dateGen" name="date_arrivee"/>
<label>Date de départ</label>
<input type="text" class="dateGen" name="date_depart" value="<?php if(isset($_POST['date_depart'])) echo $_POST['date_depart'];?>"/>
<label>Prix</label>
<input type="text" name="prix" value="<?php if(isset($_POST['prix'])) echo $_POST['prix'];?>"/>
<label>Salle</label>
<select name="salle">
<?php
foreach($salles as $unit){
    //var_dump($unit);
   echo '<option value="'.$unit->getIdSalle().'">'.$unit->getTitre().'  -  '.ucfirst($unit->ville).'  -  '.$unit->capacite.' pers.</option>';

}
?>
</select>
<label>Code promo</label>
<select name="promo">
<?php
foreach($promotion as $value){
    echo '<option value="'.$value->getIdPromo().'">'.$value->getCodePromo().'</option>';
}
?>
</select>
<label>Etat de la salle</label>
<select name="etat">
    <option value="0" selected="selected">Libre</option>
    <option value="1">Reservée</option>
</select>
<input type="submit" id="submit" name="submit" value="Ajouter une salle" />
</form>
    


