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
 * @package    local
 * @subpackage clean_urls
 * @author     Brendan Heywood <brendan@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*

This is what all traffic should be routed to in apache, which doesn't match an existing file or directory


 <Directory /var/www/example.com>
   RewriteEngine on
   RewriteBase /
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ local/clean_urls/router.php?q=$1 [L,QSA]
</Directory>

*/

require('../../config.php');
require_once('lib.php');

global $CFG;

$realuri = local_clean_urls_unclean($_GET['q'], false);
$url = new moodle_url($realuri);

foreach ($url->params() as $k => $v){
    $_GET[$k] = $v;
}

$file = $CFG->dirroot . '/' . $url->out_omit_querystring();

# TODO protect from invalid dir

chdir(dirname($file));

# TODO protect from intrusion attacks eg '../../../etc'

include ($file);
