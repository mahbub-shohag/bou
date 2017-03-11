<?php
include_once '../lib/DbManager.php';
include '../body/header.php';
//print_r($_GET);exit;
//$search_item="";
$search_item = $_GET;
//print_r($search_item);exit;
//echo $search_item['yearId'];exit;
$where = " where 1=1 ";


//echo $search_item['yearId'];exit;
//if (!empty($search_item['new_registration_id_from'])) {
if (!empty($search_item['new_registration_id_from']) && !empty($search_item['new_registration_id_to'])) {    
    //$where = $where . "AND learners_registration_id >=" . $search_item['new_registration_id_from'] ." AND learners_registration_id<=" . $search_item['new_registration_id_to'] . " ";} 
    $where = $where . "AND learners_registration_id BETWEEN " . $search_item['new_registration_id_from'] ." AND " . $search_item['new_registration_id_to'] . " "; 
}
else if(!empty($search_item['new_registration_id_from'])){
    $where = $where . "AND learners_registration_id =" . $search_item['new_registration_id_from'] . " ";
}
//}

if(!empty($search_item['year_id'])){
   $where =$where."AND year =". $search_item['year_id']." "; 
}
if(!empty($search_item['year']))
{
   $where =$where."AND year =". $search_item['year']." "; 
}
if(!empty($search_item['batches_id']))
{
   $where = $where."AND batches_id=".$search_item['batches_id']." "; 
}
if(!empty($search_item['study_groups_id']))
{
   $where = $where."AND learners.study_groups_id=".$search_item['study_groups_id']." "; 
}
if(!empty($search_item['force_id']))
{
   $where = $where."AND learners.force_id=".$search_item['force_id']." "; 
}
if(!empty($search_item['study_centers_id']))
{
   $where = $where."AND learners.study_centers_id=".$search_item['study_centers_id']." "; 
}
if(!empty($search_item['statuses_id']))
{
   $where = $where."AND learners.statuses_id=".$search_item['statuses_id']." ";  
}
//echo $where;exit;
$query = "SELECT
        learners.learners_registration_id,
	learners.learners_id,
	learners.learners_name,
	learners.fathers_name,
	study_centers.study_centers_name
FROM
	learners
LEFT JOIN study_centers ON learners.current_study_centers_id = study_centers.study_centers_id"
.$where;
//echo $query;exit;
$items = rs2array(sql($query)); 
//print_r($items);exit;
?>
<fieldset class="center_fieldset" style="width:95%;">
        <legend> Learners List</legend>  
         <table class="common_view">
                <thead>
                    <tr class="fitem">
                        <th>Learners Registration</th>
                        <th field="group">Learner ID </th>
                        <th field="quantity">Learner Name</th>
                        <th field="quantity">Father's Name </th>
                        <th field="group">Current Study Center</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                       foreach ($items as $item)
                       {
                    ?>
                    <tr>
                        <td><?php echo $item[0]; ?></td>
                        <td><?php echo $item[1]; ?></td>
                        <td><?php echo $item[2]; ?></td>
                        <td><?php echo $item[3]; ?></td>
                        <td><?php echo $item[4]; ?></td>
                        
                    </tr>
                       <?php } ?>
                </tbody>
         </table>      
 </fieldset>

<?php
include '../body/footer.php';
?>