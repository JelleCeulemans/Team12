<?php

function form_dropdown_pro($valuefield, $textfield, $name = '', $objects = array(), $selected = array(), $extra = '')
{
    $options[0] = '-- Select --';
    foreach ($objects as $object) {
        $options[$object->{$valuefield}] = $object->{$textfield};
    }

    return form_dropdown($name, $options, $selected, $extra);
}

function form_radiogroup_pro($valuefield, $textfield, $name = '', $objects = array())
{
    $result = '';

    $i = 0;
    foreach ($objects as $object) {
        $data = array('name' => $name,
            'id' => $name . $i,
            'value' => $object->{$valuefield});

        $result .= "<div>" . form_radio($data) . $object->{$textfield} . "</div>\n";
        $i++;
    }

    return $result;
}

function form_listbox_pro($name, $objects, $valuefield, $textfield, $selected = array(), $extra = array())
{
    $options = array();
    foreach ($objects as $object) {
        $options[$object->{$valuefield}] = $object->{$textfield};
    }

    return form_dropdown($name, $options, $selected, $extra);
}

function form_WachtlijstClass($name,$class, $objects, $valuefield, $textfield,$textfield2, $selected = array(), $extra = array())
{
    $options = array();
    foreach ($objects as $object) {
        $options[$object->{$valuefield}] = $object->{$textfield}." ".$object->{$textfield2};
    }

    return form_dropdownClass($name, $options, $selected, $extra,$class);
}

function form_KlassenClass($name,$class, $objects, $valuefield, $textfield,$selected = array(), $extra = array())
{
    $options = array();
    foreach ($objects as $object) {
        $options[$object->{$valuefield}] = $object->{$textfield};
    }

    return form_dropdownClass($name, $options, $selected, $extra,$class);
}

function form_dropdownClass($data = '', $options = array(), $selected = array(), $extra = '',$class)
{
    $defaults = array();

    if (is_array($data))
    {
        if (isset($data['selected']))
        {
            $selected = $data['selected'];
            unset($data['selected']); // select tags don't have a selected attribute
        }

        if (isset($data['options']))
        {
            $options = $data['options'];
            unset($data['options']); // select tags don't use an options attribute
        }
    }
    else
    {
        $defaults = array('name' => $data);
    }

    is_array($selected) OR $selected = array($selected);
    is_array($options) OR $options = array($options);

    // If no selected state was submitted we will attempt to set it automatically
    if (empty($selected))
    {
        if (is_array($data))
        {
            if (isset($data['name'], $_POST[$data['name']]))
            {
                $selected = array($_POST[$data['name']]);
            }
        }
        elseif (isset($_POST[$data]))
        {
            $selected = array($_POST[$data]);
        }
    }

    $extra = _attributes_to_string($extra);

    $multiple = (count($selected) > 1 && stripos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';

    $form = '<select '.rtrim(_parse_form_attributes($data, $defaults)).$extra.$multiple.">\n";

    foreach ($options as $key => $val)
    {
        $key = (string) $key;

        if (is_array($val))
        {
            if (empty($val))
            {
                continue;
            }

            $form .= '<optgroup label="'.$key."\">\n";

            foreach ($val as $optgroup_key => $optgroup_val)
            {
                $sel = in_array($optgroup_key, $selected) ? ' selected="selected"' : '';
                $form .= '<option class="'.$class.'" value="'.html_escape($optgroup_key).'"'.$sel.'>'
                    .(string) $optgroup_val."</option>\n";
            }

            $form .= "</optgroup>\n";
        }
        else
        {
            $form .= '<option class="'.$class.'" value="'.html_escape($key).'"'
                .(in_array($key, $selected) ? ' selected="selected"' : '').'>'
                .(string) $val."</option>\n";
        }
    }

    return $form."</select>\n";
}

function form_DropdownAantal ($aantal, $id = '', $name = '', $selected = '') {
    $dropdown = array();
    for ($i = 1; $i <= $aantal; $i++) {
        $dropdown[$i] = $i;
    }
    return form_dropdown($name,$dropdown, $selected, array('class' => 'form-control', 'id' => 'aantal'.$id));
}