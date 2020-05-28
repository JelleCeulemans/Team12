<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Authex
    {
        public function __construct()
        {
            $CI =& get_instance();
            $CI->load->model('Login_model');
        }

        function registreer () {
            $CI =& get_instance();
            $CI->Login_model->insert();
        }

        function aanmelden ($username, $wachtwoord) {
            $CI =& get_instance();
            $login = $CI->Login_model->aanmelden($username, $wachtwoord);
            $gebruikersnaam = '';
            if ($login) {
                $CI->session->set_userdata('rechten', $login->isBeheerder);
                $gebruikersnaam = $login->voornaam . ' ' . $login->achternaam;
                $CI->session->set_userdata('gebruikersnaam', $gebruikersnaam);
                $CI->session->set_userdata('userId', $login->id);

            }
            return $login;
        }

        function afmelden () {
            $CI =& get_instance();
            $CI->session->unset_userdata('rechten');
            $CI->session->unset_userdata('gebruikersnaam');
            $CI->session->unset_userdata('userId');
        }
    }
