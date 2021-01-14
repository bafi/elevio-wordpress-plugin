<?php

require_once 'ElevioHelper.class.php';

class TrackingCodeInfoHelper extends ElevioHelper
{
    public function render()
    {
        if (Elevio::get_instance()->is_installed()) {
            ?>
			<div class="updated installed_ok">
            <p>You've successfully installed Elevio, nice work (using account id: <strong>
                <?php echo Elevio::get_instance()->get_account_id() ?>
            </strong> and secret id: <strong>
                <?php echo Elevio::get_instance()->get_secret_id() ?>
            </strong>)</p></div>

            <div class="postbox">
                <form method="post" action="?page=elevio_settings">
                    <div class="postbox_content">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="elevio_is_enabled">Show elevio on site?</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="elevio_is_enabled" id="elevio_is_enabled" value="1" <?php echo Elevio::get_instance()->is_enabled() ? 'checked="checked"' : ''; ?> />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="elevio_version">I would like to load version: </label>
                                </th>
                                <td>
                                    <select name="elevio_version" id="elevio_version" >
                                        <?php foreach ([3, 4] as $version) { ?>
                                            <option <?php echo Elevio::get_instance()->get_version() === $version ? 'selected="selected"' : ''?>><?php echo $version; ?></option>
                                        <?php } ?>
                                    </select>
                                    (Version 4 is recommended)
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="elevio_category_taxonomy">Category taxonomy:</label>
                                </th>
                                <td>
                                    <select name="elevio_category_taxonomy" id="elevio_category_taxonomy" >
                                        <?php foreach (get_taxonomies() as $term) { ?>
                                            <option <?php echo Elevio::get_instance()->get_category_taxonomy() === $term ? 'selected="selected"' : ''?>><?php echo $term; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="elevio_post_taxonomy">Post taxonomy:</label>
                                </th>
                                <td>
                                   <select name="elevio_post_taxonomy" id="elevio_post_taxonomy" >
                                        <?php foreach (get_post_types() as $term) { ?>
                                            <option <?php echo Elevio::get_instance()->get_post_taxonomy() === $term ? 'selected="selected"' : ''?>><?php echo $term; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="elevio_post_taxonomy">Tag taxonomy:</label>
                                </th>
                                <td>
                                   <select name="elevio_tag_taxonomy" id="elevio_tag_taxonomy" >
                                        <?php foreach (get_taxonomies() as $term) { ?>
                                            <option <?php echo Elevio::get_instance()->get_tag_taxonomy() === $term ? 'selected="selected"' : ''?>><?php echo $term; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                        </table>

                        <p class="submit">
                            <input type="hidden" name="settings_form" value="1">
                            <input type="hidden" name="elevio_enable_form" value="1">
                            <input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" />
                        </p>
                    </div>
                </form>
            </div>
            <?php
        }

        return '';
    }
}
