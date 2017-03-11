<?php
include_once '../lib/DbManager.php';
//print_r($_POST);exit;
$search_item = $_POST;
//print_r($search_item);exit;
//echo $search_item['yearId'];exit;
$where = " where 1=1 ";
//echo $search_item['yearId'];exit;
if(!empty($search_item['year_id']))
{
   $where =$where."AND year =". $search_item['year_id']." "; 
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
   $where = $where."AND force_id=".$search_item['force_id']." "; 
}
if(!empty($search_item['study_centers_id']))
{
   $where = $where."AND study_centers_id=".$search_item['study_centers_id']." "; 
}
//echo $where;exit;
$query = "SELECT
learners.study_groups_id,
study_groups.study_groups_name AS Group_Name,
SUM(
CASE WHEN learners.statuses_id=1 THEN 1 ELSE 0 END
		
	) AS Number_Of_Active_Learners,
SUM(
CASE WHEN learners.statuses_id=2 THEN 1 ELSE 0 END
		
	) AS Number_Of_Inactive_Learners
FROM
learners
INNER JOIN study_groups ON learners.study_groups_id = study_groups.study_groups_id"
.$where.
"GROUP BY study_groups.study_groups_name";
//echo $query;exit;
$items = rs2array(sql($query)); 
//print_r($items);exit;
?>
<fieldset class="center_fieldset" style="width:95%;">
        <legend> Learners Summery</legend>  
         <table class="common_view">
                <thead>
                    <tr class="fitem">
                        <th field="group">Group Name </th>
                        <th field="quantity">Number Of Active Learners </th>
                        <th field="quantity">Number Of Inactive Learners </th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                       foreach ($items as $item)
                       {
                           //print_r($item);exit;
                    ?>
                    <tr>
                        <td><?php echo $item[1]; ?></td>
                        <td><a target="_blank" href="learners_list.php?year=<?php echo $search_item['year_id']; ?>&batches_id=<?php echo $search_item['batches_id']; ?>&study_groups_id=<?php echo $search_item['study_groups_id']; ?>&study_groups_id=<?php echo $item[0]; ?>&force_id=<?php echo $search_item['force_id']; ?>"><?php echo $item[2]; ?></a></td>
                        <td><a target="_blank" href="learners_list.php?statuses_id=2&year_id=<?php echo $search_item['year_id']; ?>&batches_id=<?php echo $search_item['batches_id']; ?>&study_groups_id=<?php echo $search_item['study_groups_id']; ?>&study_groups_id=<?php echo $item[0]; ?>&force_id=<?php echo $search_item['force_id']; ?>"><?php echo $item[3]; ?></a></td>
                    </tr>
                       <?php } ?>
                </tbody>
         </table>      
 </fieldset>
