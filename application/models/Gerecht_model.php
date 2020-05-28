<?php
/**
 * @class Gerecht_model
 * @brief Model-klasse voor gerechten
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel gerecht.
 */
class Gerecht_model extends CI_Model
{

    /** Delete een gerecht uit de database.
     * @param $id de id van het gerecht dat je wilt verwijderen.
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('gerecht');
    }

    /** Haalt alle gerechten uit de database met een bepaalde naam.
     * @param $gerecht de naam van het gerecht dat je wilt zoeken.
     * @return mixed
     */
    function getWhereNaam($gerecht)
    {
        $this->db->where('naam', $gerecht);
        $query = $this->db->get('gerecht');
        return $query->row();
    }

    /**Haalt alle gerechten uit de database.
     * @return array
     */
    function getGerechten(){
        $this->db->order_by('naam', 'asc');
        $query = $this->db->get('gerecht');
        return $query->result();
    }

    /**Haalt een bepaalt gerecht uit de database.
     * @param $gerechtId de id van het gerecht dat je uit de database wilt halen.
     * @return mixed
     */
    function getGerecht($gerechtId)
    {
        $this->db->where('id', $gerechtId);
        $query = $this->db->get('gerecht');
        return $query->row();
    }

    /** Een gerecht wordt in de database gestoken.
     * @param $gerecht het gerechtobject dat je in de database wilt steken.
     * @return int de id van het geinserte gerecht
     */
    function insert($gerecht)
    {
        $this->db->insert('gerecht', $gerecht);
        return $this->db->insert_id();

    }

    /** Een gerecht wordt in de database geupdate.
     * @param  Het gerechtobject dat je in de database wilt updaten.
     */
    function update($gerecht)
    {
        $this->db->where('id', $gerecht->id);
        $this->db->update('gerecht', $gerecht);
    }




}