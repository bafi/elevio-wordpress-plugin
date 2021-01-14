<?php

require_once 'ElevioHelper.class.php';

class TrackingCodeHelper extends ElevioHelper
{
    public function render()
    {
        if (Elevio::get_instance()->is_installed()) {
            $version = Elevio::get_instance()->get_version();
            $account_id = Elevio::get_instance()->get_account_id();
            $secret_id = Elevio::get_instance()->get_secret_id();

            $user = '';
            if (is_user_logged_in()) {
                $user_info = wp_get_current_user();
                $roles = $user_info->roles;
                $user = "

_elev.user = {
    first_name: '".$user_info->user_firstname."',
    last_name: '".$user_info->user_lastname."',
    email: '".$user_info->user_email."',
    user_hash: '".hash_hmac('sha256', $user_info->user_email, $secret_id)."',
    groups: '".implode(',', $roles)."'
};
";
            }

            if ($version == 4) {
                return <<<HTML
<script type="text/javascript">
var _elev = window._elev || {};
_elev.account_id = '{$account_id}';
(function () { var i,e;i=document.createElement('script'),i.type='text/javascript';i.async=1,i.src='https://cdn.elev.io/sdk/bootloader/v4/elevio-bootloader.js?cid={$account_id}',e=document.getElementsByTagName('script')[0],e.parentNode.insertBefore(i,e);})();
{$user}
</script>
HTML;
            } else {
                return <<<HTML
<script type="text/javascript">
var _elev = window._elev || {};(function() {var i,e;i=document.createElement("script"),i.type='text/javascript';i.async=1,i.src="https://static.elev.io/js/v3.js",e=document.getElementsByTagName("script")[0],e.parentNode.insertBefore(i,e);})();
_elev.account_id = '{$account_id}';
{$user}
</script>
HTML;
            }
        }

        return '';
    }
}
