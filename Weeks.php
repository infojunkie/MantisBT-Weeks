<?php
/**
 * Weekly Versions
 * Copyright (C) Karim Ratib (karim@meedan.com)
 *
 */

class WeeksPlugin extends MantisPlugin {
    function register() {
        $this->name = plugin_lang_get( 'title' );
        $this->description = plugin_lang_get( 'description' );
        //$this->page = 'config_page';
        $this->version = '1.0';
        $this->requires = array(
            'MantisCore' => '2.0.0',
        );
        $this->author = 'Karim Ratib';
        $this->contact = 'karim@meedan.com';
        $this->url = 'http://code.meedan.com';
        $this->nonce = crypto_generate_uri_safe_nonce( 16 );
    }

    function install() {
        return true;
    }

    function config() {
        return array(
          'project_settings' => array(),
        );
    }

    function hooks() {
        return array(
          'EVENT_LAYOUT_RESOURCES' => 'resources',
          'EVENT_CORE_HEADERS' => 'csp_headers',
        );
    }

    function resources($event) {
      $currentUrl = explode('/', $_SERVER['PHP_SELF']);
      if (end($currentUrl) !== 'manage_proj_edit_page.php') return;

      $javascript = plugin_file('Weeks.js');
      parse_str($_SERVER['QUERY_STRING'], $query);
      $form_project_id = $query['project_id'];
      $form_action = plugin_page('weeks');
      $form_security_field = form_security_field( 'plugin_Weeks_weeks' );
      $form_button_label = plugin_lang_get('btn_weekly_versions');
      $form_select_dow = join(array_map(function($day) {
        $day_lower = strtolower($day);
        return "<option value=\"{$day_lower}\">{$day}</option>";
      }, ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']));
      $form_select_year = join(array_map(function($year) {
        return "<option value=\"{$year}\">{$year}</option>";
      }, range(date("Y"), date("Y")+10)));
      $form_html = <<<FORM
      <form method="post" action="{$form_action}" class="form-inline">
    		<fieldset>
    			{$form_security_field}
    			<input type="hidden" class="form-control input-sm" name="project_id" value="{$form_project_id}" />
          <select name="dow">{$form_select_dow}</select>
          <select name="year">{$form_select_year}</select>
    			<input type="submit" name="weekly" class="btn btn-sm btn-primary btn-white btn-round" value="{$form_button_label}" />
    		</fieldset>
    	</form>
FORM;
      $form_json = json_encode($form_html);

      return <<<WEEKS
      <script type="text/javascript" nonce={$this->nonce}>
        var weeks_form = {$form_json};
      </script>
      <script type="text/javascript" src="{$javascript}"></script>
WEEKS;
    }

    /**
     * Add Content Security Policy headers for our script.
     */
    function csp_headers() {
        http_csp_add( 'script-src', "'nonce-{$this->nonce}'" );
    }
}
