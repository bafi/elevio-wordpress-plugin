<?php

require_once 'ElevioHelper.class.php';

class SettingsHelper extends ElevioHelper
{
    public function render()
    {
        ?>
		<div id="elevio">
		<div class="wrap">

		<div id="logo">
			<img src="<?php echo Elevio::get_instance()->get_plugin_url(); ?>/images/logo.png" />
			<span>for WordPress</span>
		</div>
		<div class="clear"></div>

<?php
Elevio::get_instance()->get_helper('ChangesSaved');
        Elevio::get_instance()->get_helper('TrackingCodeInfo'); ?>

		<?php if (Elevio::get_instance()->is_installed() == false) { ?>
		<div class="metabox-holder">
			<div class="postbox">
				<p>If you do not have an elevio account yet, <a target="_blank" href="https://elev.io/register">head here to create one</a> (it's super easy).</p>
			</div>
		</div>

		<div class="postbox">
			<form method="post" action="?page=elevio_settings">
				<h3>Elevio account</h3>
				<div class="postbox_content">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="elevio_account_id">My elevio account id is:</label>
							</th>
							<td>
								<input type="text" name="account_id" id="elevio_account_id" value="<?php echo Elevio::get_instance()->get_account_id(); ?>" size="40" />
								<p><small>(don't know your account id? <a target="_blank" href="https://elev.io/app/settings">click here to find it</a>)</small></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="elevio_secret_id">My elevio secret is:</label>
							</th>
							<td>
								<input type="text" name="secret_id" id="elevio_secret_id" value="<?php echo Elevio::get_instance()->get_secret_id(); ?>" size="40" />
								<p><small>(don't know your secret? <a target="_blank" href="https://elev.io/app/settings">click here to find it</a>)</small></p>
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
		                            <?php }?>
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
		                            <?php }?>
		                        </select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="elevio_tag_taxonomy">Tag taxonomy:</label>
							</th>
							<td>
	                            <select name="elevio_tag_taxonomy" id="elevio_tag_taxonomy" >
	                                <?php foreach (get_taxonomies() as $term) { ?>
	                                    <option <?php echo Elevio::get_instance()->get_tag_taxonomy() === $term ? 'selected="selected"' : ''?>><?php echo $term; ?></option>
	                                <?php }?>
	                            </select>
							</td>
						</tr>
					</table>

					<p class="ajax_message"></p>
					<p class="submit">
						<input type="hidden" name="settings_form" value="1">
						<input type="submit" class="button-primary" value="<?php _e('Save changes') ?>" />
					</p>
				</div>
			</form>
		</div>

		<?php } ?>

			<?php if (Elevio::get_instance()->is_installed()) { ?>
			<p id="reset_settings">Something went wrong? <a href="?page=elevio_settings&amp;reset=1">Reset your settings</a>.</p>
			<?php } ?>
		</div>

	</div>
	</div>
<?php
    }
}
