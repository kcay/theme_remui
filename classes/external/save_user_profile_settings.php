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
 * Save user profile settings service
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

use external_function_parameters;
use context_user;
use external_value;

/**
 * Save user profile settings trait
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait save_user_profile_settings {
    /**
     * Describes the parameters for save_user_profile_settings
     * @return external_function_parameters
     */
    public static function save_user_profile_settings_parameters() {
        return new external_function_parameters(
            array(
                'fname' => new external_value(PARAM_TEXT, 'Firstname'),
                'lname' => new external_value(PARAM_TEXT, 'Lastname'),
                'description' => new external_value(PARAM_RAW, 'Description'),
                'city' => new external_value(PARAM_TEXT, 'City'),
                'country' => new external_value(PARAM_ALPHAEXT, 'Country'),
                'phonenumber' => new external_value(PARAM_TEXT, 'Phonenumber'),
                'department' => new external_value(PARAM_TEXT, 'Department'),
                'address' => new external_value(PARAM_RAW, 'Address')
            )
        );
    }

    /**
     * Save user profile settings submitted in the profile page
     * @param  string $fname       Firstname
     * @param  string $lname       Lastname
     * @param  string $description Description
     * @param  string $city        City
     * @param  string $country     Country
     * @return mixxed              Result
     */
    public static function save_user_profile_settings($fname, $lname, $description, $city, $country, $phonenumber, $department, $address) {
        global $USER;
        // Validation for context is needed.
        $context = context_user::instance($USER->id);
        self::validate_context($context);

        $result = \theme_remui\usercontroller::save_user_profile_info(
            $fname,
            $lname,
            $description,
            $city,
            $country,
            $phonenumber,
            $department,
            $address);

        if($result) {
            $updatedProfileData = array(
                'description' => format_text($description, FORMAT_HTML),
                'city' => format_text($city, FORMAT_HTML),
                'department' => format_text($department, FORMAT_HTML),
                'address' => format_text($address, FORMAT_HTML)
            );
        } else {
            $updatedProfileData = [];
        }

        return json_encode($updatedProfileData);
    }

    /**
     * Describes the save_user_profile_settings return value
     * @return external_value
     */
    public static function save_user_profile_settings_returns() {
        return new external_value(PARAM_RAW, 'updatedProfileData');
    }
}
