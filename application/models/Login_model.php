<?php
/**
 * @class Login_model
 * @brief Model-klasse voor logins
 *
 * Model-klasse die alle methodes bevat om te interageren met de database-tabel login.
 */
class Login_model extends CI_Model
{
    /**
     * Constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Functie voor het nagaan of de ingegeven login gegevens in de database voorkomen.
     * @param $username de gebruikersnaam van de gebruiker
     * @param $wachtwoord het wachtwoord van de gebruiker
     * @return bij het correcte gegevens word het record van de gebruiker geretourneert anders wordt null geretourneert.
     */
    function aanmelden($username, $wachtwoord)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('login');

        if ($query->num_rows() == 1) {
            $login = $query->row();
            if (password_verify($wachtwoord, $login->wachtwoord)) {
                return $login;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /** Haalt een bepaalde login uit de database.
     * @param $id De id van de login die je wilt oproepen.
     * @return mixed loginobject
     */
    function get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('login');
        return $query->row();
    }

    /** Haalt alle logins uit de database geordend op username.
     * @return array van loginobjecten
     */
    function getAllByNaam()
    {
        $this->db->order_by('username', 'asc');
        $query = $this->db->get('login');
        return $query->result();
    }

    /** Haalt een login uit de database met een bepaalde username.
     * @param $gebruikernaam De username van de login die je wilt ophalen.
     * @return mixed
     */
    function getWhereNaam($gebruikernaam)
    {
        $this->db->where('username', $gebruikernaam);
        $query = $this->db->get('login');
        return $query->row();
    }

    /** Voegt een nieuwe gebruiker toe in de database.
     * @param $gebruiker het loginobject dat je wilt toevoegen.
     * @return int De id van de geinserte object.
     */
    function insert($gebruiker)
    {
        $gebruiker->wachtwoord = password_hash($gebruiker->wachtwoord, PASSWORD_DEFAULT);
        $this->db->insert('login', $gebruiker);
        return $this->db->insert_id();
    }

    /** Update een gebruiker in de database.
     * @param $gebruiker Het loginobject dat je wilt updaten.
     */
    function update($gebruiker)
    {
        $this->db->where('id', $gebruiker->id);
        $this->db->update('login', $gebruiker);
    }

    /** Delete een gebruiker in de database.
     * @param $id De id van de gebruiker die je wilt deleten.
     */
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('login');
    }

    /** veranderd het wachtwoord van een gebruiker naar een random gegenereert wachtwoord en mailt dit door.
     * @param $email De email naar waar het nieuwe wachtwoord gestuurd moet worden.
     * @return null|string De inhoud van de mail.
     */
    function veranderWachtwoordHerstellen($email){

        $this->db->where('email', lcfirst($email));
        $query = $this->db->get('login');

        if ($query->num_rows() == 1) {
            $gebruiker = $query->row();
        } else {
            return null;
        }
        $wachtwoord =$this->randomPassword();

        $gebruiker->wachtwoord= password_hash($wachtwoord,PASSWORD_DEFAULT);
        $this->db->where('id',$gebruiker->id);
        $this->db->update('login', $gebruiker);
        $boodschap='Beste '.$gebruiker->achternaam.' '.$gebruiker->voornaam.' uw nieuwe wachtwoord is: '.$wachtwoord;
        return $boodschap;
    }

    /** Genereert een random wachtwoord.
     * @return string het random gegenereerde wachtwoord.
     */
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}