<?php
/**
 * @class Nieuws_model
 * @brief Model-klasse voor webinhoud
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel webinhoud.
 */
class Nieuws_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**Haalt een bepaald nieuwtje uit de database.
     * @param $id De id van het nieuwtje dat je wilt oproepen.
     * @return mixed
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('webinhoud');
        return $query->row();
    }

    /** Haalt alle nieuwtjes uit de database geordend op datum.
     * @return array van nieuwtjes.
     */
    function getAllByDate()
    {
        $this->db->order_by('datum', 'desc');
        $query = $this->db->get('webinhoud');
        return $query->result();
    }

    /** Telt het aantal nieuwtjes in de tabel webinhoud.
     * @return int aantal nieuwtjes.
     */
    function getCountAll()
    {
        return $this->db->count_all('webinhoud');
    }

    /** Haalt een aantal nieuwtjes uit de database startend vanaf een bepaalde rij.
     * @param $aantal Het aantal nieuwtjes dat je uit de database wilt halen.
     * @param $startRow De rij waarvan je wilt beginnen.
     * @return array van niewtjes.
     */
    function getNextByDate($aantal, $startRow)
    {
        $this->db->order_by('datum', 'desc');
        $query = $this->db->get('webinhoud', $aantal, $startRow);
        return $query->result();
    }

    /** Een nieuwtje wordt in de database gestoken.
     * @param $nieuws het nieuwsobject dat je in de database wilt steken.
     * @return int De id van het geinserte nieuwtje.
     */
    function insert($nieuws)
    {
        $this->db->insert('webinhoud', $nieuws);
        return $this->db->insert_id();

    }

    /** Een nieuwtje wordt in de database geupdate.
     * @param $nieuws Het nieuwsobject dat je in de database wilt updaten.
     */
    function update($nieuws)
    {
        $this->db->where('id', $nieuws->id);
        $this->db->update('webinhoud', $nieuws);
    }

    /** Een nieuwtje dat je wilt verwijderen.
     * @param $id De id van het nieuwtje dat je wilt verwijderen.
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('webinhoud');
    }
}