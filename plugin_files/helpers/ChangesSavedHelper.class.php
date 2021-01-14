<?php

require_once 'ElevioHelper.class.php';

class ChangesSavedHelper extends ElevioHelper
{
    public function render()
    {
        if (Elevio::get_instance()->changes_saved()) {
            return '<div id="changes_saved_info" class="updated installed_ok"><p>Advanced settings saved successfully.</p></div>';
        }

        return '';
    }
}
