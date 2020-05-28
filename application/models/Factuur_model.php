<?php
/**
 * @class Factuur_model
 * @brief Model-klasse voor facturen
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel factuur.
 */
class Factuur_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Een leeg moment wordt aangemaakt
     */
    function getLeegFactuur(){

        $factuur = new stdClass();

        $factuur->id =0;
        $factuur->schoolId=0;
        $factuur->prijs=0;

        return $factuur;
    }

    /** Haalt alle facturen uit de database van een bepaalde school.
     * @param $schoolId de id van de school waar je de facturen van wilt oproepen.
     * @return array van de schoolgegevens.
     */
    function getAllWhereSchool($schoolId)
    {
        $this->db->where('schoolId', $schoolId);
        $query = $this->db->get('factuur');
        return $query->result();
    }

    /** Een factuur wordt in de database gestoken
     * @param $factuur het factuurobject dat je in de database wilt steken.
     * @return int de id van het geinserte object.
     */
    function insert($factuur)
    {
        $this->db->insert('factuur', $factuur);
        return $this->db->insert_id();

    }

    /** Verwijderd alle facturen uit de database van een beppaalde school.
     * @param $schoolId de id van de school waar je de facturen van wilt verwijderen.
     */
    function deleteAllWhereSchool($schoolId)
    {
        $this->db->where('schoolId', $schoolId);
        $this->db->delete('factuur');
    }

    /** Verwijderd een bepaalde factuur.
     * @param $id
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('factuur');
    }
}