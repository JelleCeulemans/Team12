<?php
/**
 * @class Videodemo_model
 * @brief Model-klasse voor videodemo
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel videodemo.
 */
class Videodemo_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /** Haalt een videodemo uit de database voor een bepaalde gebruiker.
     * @param $gebruikerId De id van de gebruiker waarvoor je de videodemo wilt ophalen.
     * @return mixed videodemoobject.
     */
    function getWhereGebruiker($gebruikerId){
        $this->db->where('loginid', $gebruikerId);
        $query = $this->db->get('videodemo');
        return $query->row();
    }

    /** Veranderd de videodemo naar bekeken.
     * @param $loginId De id van de gebruiker die geupdate moet worden.
     */
    function nooitTonen($loginId) {
        $videodemo = $this->getWhereGebruiker($loginId);
        $videodemo->bekeken = 1;

        $this->db->where('id', $videodemo->id);
        $this->db->update('videodemo', $videodemo);
    }
}