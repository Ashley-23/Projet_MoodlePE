<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 * 
 * @package     local_moodlepe
 * @category    string
 * @copyright   2022 SATCHIVI Kokoè Yasmine Ashley <ashleysatchivi92@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class local_moodlepe_message_form extends moodleform
{
    /**
     * Define the form.
     */
    public function definition()
    {
        $mform    = $this->_form; // Don't forget the underscore!

        // FIRSTNAME
        $mform->addElement('text', 'message', get_string('firstname', 'local_moodlepe')); // Add elements to your form.
        $mform->setType('message', PARAM_TEXT); // Set type of element.

        // LASTNAME
        $mform->addElement('text', 'message', get_string('lastname', 'local_moodlepe')); // Add elements to your form.
        $mform->setType('message', PARAM_TEXT); // Set type of element.

        // USERNAME
        $mform->addElement('text', 'message', get_string('username', 'local_moodlepe')); // Add elements to your form.
        $mform->setType('message', PARAM_TEXT); // Set type of element.

        // EMAIL
        // $mform->addElement('email', 'message', get_string('email', 'local_moodlepe')); // Add elements to your form.
        // $mform->setType('message', PARAM_TEXT); // Set type of element.

        $mform->addElement('email', 'message', get_string('email', 'local_moodlepe'));
        $mform->setType('message', PARAM_NOTAGS);                   //Set type of element
        // $mform->setDefault('email', 'Please enter email');

        // PASSWORD
        $mform->addElement('password', 'message', get_string('password', 'local_moodlepe')); // Add elements to your form.
        $mform->setType('message', PARAM_TEXT); // Set type of element.

        //VERIFY PASSWORD
        $mform->addElement('password', 'message', get_string('verify_password', 'local_moodlepe')); // Add elements to your form.
        $mform->setType('message', PARAM_TEXT); // Set type of element.

        $submitlabel = get_string('submit');
        $mform->addElement('submit', 'submitmessage', $submitlabel);
    }
}
