<?php
/**
 * @class Bestelling_model
 * @brief Model-klasse voor bestellingen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel bestelling.
 */
class Bestelling_model extends CI_Model
{
    /**
     * Bestelling_model constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** Haal alle bestellingen uit de database die nog niet zijn afgehandeld.
     * @return array $bestellingen
     */
    function getAllNietAfgehandeld()
    {
        $this->db->where("IsAfgehandeld = 0");
        $query = $this->db->get('bestelling');
        $bestellingen = $query->result();
        return $bestellingen;

    }

    /** Update een bepaalde bestelling naar afgehandeld.
     * @param $bestellingId De id van de bestelling die je wilt afhandelen.
     */
    function afhandel($bestellingId){
        $this->db->where("id", $bestellingId);
        $query = $this->db->get('bestelling');
        $bestelling = $query->row();

        $bestelling->isAfgehandeld = 1;

        $this->db->where('id', $bestelling->id);
        $this->db->update('bestelling', $bestelling);
    }
}