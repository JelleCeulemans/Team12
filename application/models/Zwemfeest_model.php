<?php
/**
 * @class Zwemfeest_model
 * @brief Model-klasse voor zwemfeesten
 */

class Zwemfeest_model extends CI_Model
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /** haalt alle zwemfeestjes uit de database die nog niet geweest zijn.
     * @return mixed
     */
    function getAllVanafVandaag() {
        $vandaag = date('Y-m-d');

        $this->db->from('zwemfeest');
        $this->db->where("datum >= '$vandaag'");
        $query = $this->db->get();
        $zwemfeestjes = $query->result();
        return $zwemfeestjes;
    }

    /**
     * Retourneert een array voor het aanmaken van een dropdown met alle gerechten uit de tabel gerecht
     * @return array met gerechten
     */
    function getAllgerechten() {
        $query = $this->db->get('gerecht');
        $dropdown = array();
        $dropdown['0'] = 'Kies een gerecht';
        foreach ($query->result_array() as $item) {
            $dropdown[$item['id']] = ucfirst($item['naam'] . ' (€'. zetOmNaarKomma($item['prijs']).' per maaltijd)');
        }
        return $dropdown;
    }

    /**
     * Retourneert een array voor het aanmeken van een dropdown met alle momenten uit de tabel zwemfeestMoment
     * @return array met mogelijke boekbare momenten
     */
    /*function getAllMomenten() {
        $query = $this->db->get('zwemfeestMoment');
        $dropdown = array();
        $dropdown['0'] = 'Kies een moment';
        foreach ($query->result_array() as $item) {
            $dropdown[$item['id']] = ucfirst($item['weekdag'] . ' van '. $item['startuur'].' tot ' .$item['stopuur']);
        }
        return $dropdown;
    }*/

    /**
     * Retourneert een id ter herkenning van de ingegeven zwmefeest aanvraag
     * @param $zwemfeest het object zwemfeest dat wordt ingevoegd bij de aanvragen
     * @return id dat het zwmefeest object is ingevoegd.
     */
    function insert($zwemfeest) {
       $this->db->insert('zwemfeest', $zwemfeest);
       return $this->db->insert_id();
    }

    /**
     * Retourneert alle zwemfeesten die al zijn geboekt
     * @return alle zwemfeest objecten
     */
    function getAllZwemfeesten() {
        $query = $this->db->get('zwemfeest');
        return $query->result();
    }
    /**Verwijderd alle wachtlijsten met een bepaald zwemmoment uit de database.
     * @param $momentId de id van het moment waarvan je de wachtlijsten wilt verwijderen.
     */
    function deleteWhereMoment($momentId){

        $this->db->where('zwemfeestMomentId', $momentId);
        $this->db->delete('zwemfeest');
    }

    /**
     * Haalt alle momenten wanneer er een zwemfeest geboekt kan worden voor de gekozen weekdag.
     * @param $weekdag de weekdag in getal vorm
     * @return $dropdown array met alle boekbare momenten
     */
    function momentenOphalen($weekdag) {
        $this->db->where('weekdag', $weekdag);
        $query = $this->db->get('zwemfeestMoment');

        $dropdown = array();
        $dropdown['0'] = 'Kies een moment';
        foreach ($query->result_array() as $item) {
            $dropdown[$item['id']] = 'Van '. $item['startuur'].' tot ' .$item['stopuur'];
        }
        return $dropdown;
    }

    /** Verwijderd alle zwemfeestjes met een bepaalt gerecht uit de database.
     * @param $gerechtId De id van het gerecht waarvan je de zwemfeestjes wilt verwijderen.
     */
    function deleteWhereGerecht($gerechtId){

        $this->db->where('gerechtId', $gerechtId);
        $this->db->delete('zwemfeest');
    }
}