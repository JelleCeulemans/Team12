<?php
/**
 * @class Zwemmer_model
 * @brief Model-klasse voor zwemmers
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel zwemmer.
 */
class Zwemmer_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function deleteWhereNiveau($niveauId){
        /**Verwijderd alle zwemmers met een bepaald niveau uit de database.
         * @param $niveauId de id van het niveau waarvan je de zwemmer wilt verwijderen.
         */

        $this->db->where('niveauId', $niveauId);
        $this->db->delete('zwemmer');
    }

    function getAllWhereNiveau($niveauid){
        $this->db->where('niveauId', $niveauid);
        $query = $this->db->get('zwemmer');
        return $query->result();
    }
}