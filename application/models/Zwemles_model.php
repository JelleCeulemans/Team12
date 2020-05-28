<?php
/**
 * @class Zwemles_model
 * @brief Model-klasse voor zwemlessen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel zwemmer, niveau en zwemMoment
 */

class Zwemles_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Haalt alle niveaunamen op
     * @return array van alle niveau namen
     */
    function getAllNiveaus()
    {
        $query = $this->db->get('niveau');
        $dropdown = array();
        foreach ($query->result_array() as $row) {
            $dropdown[$row['id']] = $row['naam'];
        }
        return $dropdown;
    }

    /**
     * Haalt alle niveaus op
     * @return alle rijen van niveau
     */
    function getNiveaus(){
        $this->db->order_by('naam', 'asc');
        $query = $this->db->get('niveau');
        return $query->result();
    }

    /**
     * Haalt de data van een specifieke zwemmer op
     * @param $id het id van de zwemmer
     * @return alle data van deze zwemmer
     */
    function getZwemmerById($id)
    {
        $this->db->where('id',$id);
        $query = $this->db->get('zwemmer');
        return $query->row();
    }

    /**
     * Haalt de data van een specifiek niveau op
     * @param $id het id van het niveau
     * @return het id en de naam van dit niveau
     */
    function getNiveauById($id)
    {
        $this->db->where('id',$id);
        $query = $this->db->get('niveau');
        return $query->row();
    }

    /**
     * Haalt de dag en tijden op van momenten horende bij het geselecteerde niveau
     * @param $niveauId het id van het niveau
     * @return array van alle momenten horende bij het geselecteerde niveau
     */
    function getAllMomentenInfo($niveauId)
    {
        $this->db->where('niveauId',$niveauId);
        $query = $this->db->get('zwemMoment');
        $dropdown = array();
        $weekdagen = array("zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag");
        foreach ($query->result_array() as $row) {
            $dropdown[$row['id']] = $weekdagen[$row['weekdag']] . " " . $row['startuur'] . "-" . $row['stopuur'];
        }
        return $dropdown;
    }

    /**
     * Haalt alle momenten ids op
     * @param $niveauId het id van een niveau
     * @return array van alle momenten id's
     */
    function getAllMomenten($niveauId)
    {
        $this->db->order_by('weekdag asc');
        $this->db->order_by('startuur asc');
        $this->db->order_by('stopuur asc');
        $this->db->where('niveauId',$niveauId);
        $query = $this->db->get('zwemMoment');
        $dropdown = array();
        foreach ($query->result_array() as $row) {
            $dropdown[$row['id']] = $row['id'];
        }
        return $dropdown;
    }

    /**
     * Voegt een zwemmer toe aan de database
     * @param $zwemles de data van de zwemmer
     * @return id van de aangemaakte zwemmer
     */
    function insertZwemmer($zwemles) {
        $this->db->insert('zwemmer', $zwemles);
        return $this->db->insert_id();
    }

    /**
     * Update de data van een zwemmer
     * @param $zwemmer de data van de zwemmer
     */
    function update($zwemmer)
    {
        $this->db->where('id', $zwemmer->id);
        $this->db->update('zwemmer', $zwemmer);
    }

    /**
     * Delete een zwemmer
     * @param $id de id van een zwemmer
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('zwemmer');
    }

    /**
     * Voegt een zwemmer toe aan de wachtlijst in database "beschikbaar"
     * @param $zwemmer de id van een zwemmer
     * @return id van de wachtlijst
     */
    function insertZwemmerWachtlijst($zwemmer) {
        $this->db->insert('beschikbaar', $zwemmer);
        return $this->db->insert_id();
    }

    /**
     * Haalt zwemmers op gesorteerd op achternaam
     * @return alle zwemmers hun data gesorteerd op achternaam
     */
    function getZwemmers(){
        $this->db->order_by('achternaam', 'asc');
        $query = $this->db->get('zwemmer');    // genereert SELECT * FROM bmw_auto ORDER BY serie asc
        return $query->result();
    }
}