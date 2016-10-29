<?php
namespace Repository;

USE Manager\EntityRepository;
USE PDO;

class ExampleRepository extends EntityRepository
{


    public function fetchAll()
    {
        $query = $this->getDb()->prepare("SELECT a.*, m.pseudo, s.titre FROM avis a, membre m, salle s WHERE a.id_membre=m.id_membre AND a.id_salle=s.id_salle");
        $query->setFetchMode(PDO::FETCH_CLASS, '\Entity\\' . 'Example');
        $query->execute();
        return $result = $query->fetchAll();
    }

    public function objectBinder($query, $avis = array())
    {
        $cles = array('submit');
        foreach ($avis as $key => $value) {
            if (!in_array($key, $cles)) {
                $query->bindValue(":$key", $value);
            }
        }
    }

    public function deleteFeedback($id)
    {
        $query = $this->getDb()->prepare("DELETE FROM avis WHERE id_avis=$id");
        $query->execute();
    }

    public function bestRanked()
    {
        $query = $this->getDb()->prepare("SELECT s.titre, a.id_salle, ROUND(AVG(a.note),0) as note, COUNT(a.id_avis) as nbre FROM salle s, avis a WHERE a.id_salle=s.id_salle GROUP BY a.id_salle LIMIT 0,5;");
        $query->setFetchMode(PDO::FETCH_CLASS, '\Entity\\' . 'Avis');
        $query->execute();
        return $result = $query->fetchAll();
    }

}

