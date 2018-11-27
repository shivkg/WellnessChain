<?php
/**
 *
 * Copyright (C) 2016-2017 Jerry Padgett <sjpadgett@gmail.com>
 * Copyright (C) 2011 Cassian LUP <cassi.lup@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author Cassian LUP <cassi.lup@gmail.com>
 * @author Jerry Padgett <sjpadgett@gmail.com>
 * @link http://www.open-emr.org
 *
 */
    require_once("verify_session.php");
    error_reporting(1);
       if(!empty($_POST["form_desisessearchresult_ae"])){

    $sql = "SELECT id, username, fname, lname FROM users WHERE facility_id ='" .
     $_POST["form_desisessearchresult_ae"] . "'";

    $res = sqlStatement($sql);

if (sqlNumRows($res)>0) {
    ?>
    
        
    <?php
    $even=false;
    while ($row = sqlFetchArray($res)) {
         
        echo "<option value='".$row['id']."'>".$row['lname']." ". $row['fname'];
        
        echo "</option>";
    }

     
} else {
    echo xlt("No Results");
}
}
?>
