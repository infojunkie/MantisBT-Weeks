<?php
/**
 * Weekly software versions
 * Copyright (C) Karim Ratib (karim@meedan.com)
 *
 */

 require_api('version_api.php');

form_security_validate( 'plugin_Weeks_weeks' );

$f_project_id = gpc_get_int('project_id');
$t_redirect_url = 'manage_proj_edit_page.php?' . http_build_query(['project_id' => $f_project_id]);
layout_page_header( null, $t_redirect_url );
layout_page_begin();

form_security_purge( 'plugin_Weeks_weeks' );

$f_dow = gpc_get_string('dow');
$f_year = gpc_get_int('year');
create_or_update_versions($f_project_id, $f_dow, $f_year);

html_operation_successful( $t_redirect_url );
layout_page_end();

function create_or_update_versions($project_id, $dow, $year) {
  // Remember configuration.
  $old_project_settings = $project_settings = plugin_config_get('project_settings');
  $project_settings[$project_id][$year] = [
    'dow' => $dow,
  ];
  plugin_config_set('project_settings', $project_settings);

  // Create or update versions.
  for ($week=1; $week<=52; $week++) {
    $firstDow = first_day_of_week($week, $year);
    $nextDow = strtotime($dow, $firstDow);
    $nextDow = strtotime('12pm', $nextDow);
    $version_string = sprintf("Week %d/%02d", date('W', $nextDow), date('y', $nextDow));

    $version_id = version_get_id($version_string, $project_id);
    if ($version_id) {
      $version = version_get($version_id);
      $version->date_order = $nextDow;
      version_update($version);
    }
    else {
      version_add($project_id, $version_string, VERSION_FUTURE, '', $nextDow, $nextDow < time());
    }
  }
}

// http://stackoverflow.com/questions/4861384/php-get-start-and-end-date-of-a-week-by-weeknumber
function first_day_of_week($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  return $dto->format('U');
}
