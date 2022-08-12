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

require_once('../../config.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodlepe/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('name', 'local_moodlepe'));

echo $OUTPUT->header();





?>
<!-- 
<div class="form-item row" id="admin-coursecolor9">
    <div class="form-label col-sm-3 text-sm-right">
        <label for="id_s_core_admin_coursecolor9">
            Couleur 9
        </label>
        <span class="form-shortname d-block small text-muted">core_admin | coursecolor9</span>
    </div>
    <div class="form-setting col-sm-9">
        <div class="form-colourpicker defaultsnext" id="yui_3_17_2_1_1659972010767_127">
            <div class="admin_colourpicker clearfix"><img alt="" class="colourdialogue" src="http://localhost:8080/moodlepe/theme/image.php/boost/core/1659960092/i/colourpicker">
                <div class="previewcolour" style="width: 50px; height: 50px; background-color: rgb(253, 121, 168);"></div>
                <div class="currentcolour" style="width: 50px; height: 49px; background-color: rgb(253, 121, 168);"></div>
            </div>
            <input type="text" name="s_core_admin_coursecolor9" id="id_s_core_admin_coursecolor9" value="#fd79a8" size="12" class="form-control text-ltr">
        </div>
        <div class="form-defaultinfo text-muted text-ltr">Défaut&nbsp;: #fd79a8</div>
        <div class="form-description mt-3"></div>

    </div>
</div> -->

<?php
echo $OUTPUT->footer();
?>