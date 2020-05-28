<?php

    function navLink ($uri = '', $title = '', $id = '')
    {
        return "<a href='".site_url().$uri."' id='".$id."' class='list-group-item list-group-item-action text-light bg-dark' >".$title."</a>";

    }

function bootstrapImg ($uri = '', $alt = '')
{
    return "<img src='". base_url().$uri ."' alt='$alt' class'img-circle'>";

}

?>
