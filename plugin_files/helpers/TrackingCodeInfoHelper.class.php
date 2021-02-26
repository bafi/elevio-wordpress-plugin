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
                            <tr>
                                <th scope="row">
                                    <label for="elevio_multi_language_is_enabled">Support multilanguage:</label>
                                </th>
                                <td>
                                    <input type="hidden" name="elevio_multi_language_is_enabled" value="0">
                                    <input type="checkbox" name="elevio_multi_language_is_enabled" id="elevio_multi_language_is_enabled" value="1" <?php echo Elevio::get_instance()->multi_language_is_enabled() ? 'checked="checked"' : ''; ?> />
                                    (Integrated with WPML only - you should have WPML to support multilanguage)
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="elevio_aggregated_translated_articles">Aggregate translated articles:</label>
                                </th>
                                <td>
                                    <input type="hidden" name="elevio_aggregated_translated_articles" value="0">
                                    <input type="checkbox" name="elevio_aggregated_translated_articles" id="elevio_aggregated_translated_articles" value="1" <?php echo Elevio::get_instance()->aggregate_translated_articles() ? 'checked="checked"' : ''; ?> />
                                    (Make one Elevio article for each post with translation all tied to that same article - Integrated with WPML only)
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
