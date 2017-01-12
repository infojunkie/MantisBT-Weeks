
<?php
/**
 * Weekly Versions
 * Copyright (C) Karim Ratib (karim@meedan.com)
 *
 */
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

layout_page_header( plugin_lang_get( 'title' ) );

layout_page_begin( 'manage_overview_page.php' );

print_manage_menu( 'manage_plugin_page.php' );

?>

<div class="col-md-12 col-xs-12">
<div class="space-10"></div>
<div class="form-container">
<form action="<?php echo plugin_page( 'config' ) ?>" method="post">
<fieldset>
<div class="widget-box widget-color-blue2">
<div class="widget-header widget-header-small">
    <h4 class="widget-title lighter">
        <i class="ace-icon fa fa-exchange"></i>
        <?php echo plugin_lang_get( 'title' ) ?>
    </h4>
</div>

<?php echo form_security_field( 'plugin_Weeks_config' ) ?>
<div class="widget-body">
<div class="widget-main no-padding">
<div class="table-responsive">
<table class="table table-bordered table-condensed table-striped">
<?php /*
    <tr>
      <td class="category">
        <?php echo plugin_lang_get( 'url_webhooks' )?>
      </td>
      <td  colspan="2">
        <p>
          Specifies the mapping between Mantis project names and Weeks webhooks.
        </p>
        <p>
          Option name is <strong>plugin_Weeks_url_webhooks</strong> and is an array of 'Mantis project name' => 'Weeks webhook'.
          Array options must be set using the <a href="adm_config_report.php">Configuration Report</a> screen.
          The current value of this option is:<pre><?php var_export(plugin_config_get( 'url_webhooks' ))?></pre>
        </p>
      </td>
    </tr>
*/ ?>
</table>
</div>
</div>
<div class="widget-toolbox padding-8 clearfix">
    <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'action_update' ) ?>" />
</div>
</div>
</div>
</fieldset>
</form>
</div>
</div>

<?php
layout_page_end();
