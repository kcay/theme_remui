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

/**
 * Get course stats service
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

defined('MOODLE_INTERNAL') || die;

use external_function_parameters;
use external_value;

require_once($CFG->libdir . '/completionlib.php');

/**
 * Get course stats trait
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait get_dashboard_stats {
    /**
     * Describes the parameters for get_dashboard_stats
     * @return external_function_parameters
     */
    public static function get_dashboard_stats_parameters() {
        return new external_function_parameters(
            array ()
        );
    }

    /**
     * Save order of sections in array of configuration format
     * @param  int $courseid Course id
     * @return boolean       true
     */
    public static function get_dashboard_stats() {
        // Validation for context is needed.
        $context = \context_system::instance();
        self::validate_context($context);

        $coursehandler = new \theme_remui_coursehandler();

        $stats = $coursehandler->get_dashboard_stats();

        return $stats;
    }

    /**
     * Describes the get_dashboard_stats return value
     * @return external_value
     */
    public static function get_dashboard_stats_returns() {
        return new \external_single_structure(
            array (
                'coursesenrolled' => new external_value(PARAM_INT, 'Enrolled Users'),
                'coursescompleted' => new external_value(PARAM_INT, 'Students Completed'),
                'activitiescompleted' => new external_value(PARAM_INT, 'Students Inprogress'),
                'activitiesdue' => new external_value(PARAM_INT, 'Students Not Started')
            )
        );
    }
}
