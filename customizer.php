<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
require_once("../../config.php");

require_login();

if (!is_siteadmin()) {
    throw new moodle_exception(get_string('noaccessright', 'theme_remui'));
}

$url = optional_param('url', $CFG->wwwroot, PARAM_RAW);
$testurl = $_SERVER['REQUEST_URI'];
$urlparameters = strstr($testurl, '&');
$url = $url.$urlparameters;
$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_system::instance());
$PAGE->set_url($CFG->wwwroot . '/theme/remui/customizer.php?url=' . $url);
$PAGE->set_title(get_string('customizer', 'theme_remui'));

$PAGE->requires->js_call_amd('theme_remui/customizer', 'init');

$PAGE->requires->css('/theme/remui/style/customizer.css');
$PAGE->requires->css('/theme/remui/style/devices.css');
$PAGE->requires->css('/theme/remui/style/color-picker.css');

// $PAGE->requires->css( '/local/edwiserpagebuilder/styles/editor.css' );
$strings = get_string_manager()->load_component_strings('theme_remui', 'en');
$PAGE->requires->strings_for_js(array_keys($strings), 'theme_remui');
$PAGE->requires->strings_for_js(array(
    'success',
    'yes',
    'reset'
), 'moodle');

$customizer = theme_remui\customizer\customizer::instance();

$templatecontext = new stdClass;

$templatecontext->panels = $customizer->accordion();
$templatecontext->url = $url;
// $templatecontext->loader = new moodle_url('/theme/remui/pix/siteloader.gif');

if(get_config('theme_remui','enablesiteloader')){
    $templatecontext->loader = \theme_remui\utility::get_site_loader();
}
$templatecontext->sitename = $COURSE->shortname;

$templatecontext->darkmodecustomizerwarnnotvisible  = get_user_preferences('darkmodecustomizerwarnnotvisible');

$templatecontext->dmstatus = (new theme_remui_darkmodehandler())->get_status() !== "disable";

set_config("dashboardpersonalizerinfo", "hide", 'theme_remui');



$PAGE->requires->js_call_amd('theme_remui/feedbackcollection', 'init', [false]);


echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_remui/customizer/main', $templatecontext);
echo $OUTPUT->footer();
