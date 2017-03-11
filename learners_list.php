<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  
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
$query = "
SELECT
	learners.learners_id,
        learners.learners_registration_id,
	learners.learners_name,
	learners.dateofbirth,
	study_centers.study_centers_name,
        subjects.subjects_name,
	subjects.subjects_code,
	subjects.subjects_id,
	subjects.full_marks,
        registered_subjects.learners_session_id
FROM
	learners
LEFT JOIN registered_subjects ON registered_subjects.learners_id = learners.learners_id
LEFT JOIN subjects ON registered_subjects.subjects_id = subjects.subjects_id
LEFT JOIN study_centers ON learners.current_study_centers_id = study_centers.study_centers_id"
.$where;
//echo $query;exit;
$items = rs2array(sql($query)); 
//print_r($items);exit;




if($items)
{
$students = array();
$student = array();
$courses = array();
$is_first_time = 1;
foreach ( $items as $row ){
    $course = array();    
    if(!in_array($row[0], $student)){
        
        if($is_first_time != 1){
            $student['course'] = $courses;
            $courses = array();
            $students[] = $student;
            $student = array();
        }
        $is_first_time = 0;
        $student['learners_id'] = $row[0];
        $student['learners_registration_id'] = $row[1];
        $student['learners_name'] = $row[2];
        $student['dateofbirth'] = $row[3];
        $student['study_centers_name'] = $row[4];
              
    }
    
    if( $row[5] != "" ){
        $course['subjects_name'] = $row[5];
        $course['subjects_code'] = $row[6];
        $course['subjects_id'] = $row[7];
        $course['full_marks'] = $row[8];
        $course['learners_session_id'] = $row[9];
        $courses[] = $course;
        
    } 
    
}
$student['course'] = $courses;
$students[] = $student;
$largeval=0;

foreach ($students as $aitem)
{
    
    if(count($aitem['course'])>$largeval){$largeval=count($aitem['course']);}
    
}
//echo $largeval;exit;
//echo '<pre>';
//print_r($students);
//exit();
}
?>
<fieldset class="center_fieldset" style="width:95%;">
        <legend> Learners List</legend>  
         <table class="common_view">
                <thead>
                    <tr class="fitem">
                        <th field="group">#</th>
                        <th field="group">Learners Info</th>
                        <th field="group">Study Center</th>
                        <th field="quantity">Year</th>
                        <?php $x=1; for($i=0;$i<7;$i++){echo "<th field='group'>Course".$x++."</th>";} ?>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                       $sl=1;
                       if($students){foreach ($students as $astudent)
                       {
                        $thecourses = $astudent['course'];   
                        
                    ?>
                    <tr>
                        <td rowspan="4"><?php echo $sl++; ?></td>
                        <td><?php echo $astudent['learners_id']; ?></td>
                        <td rowspan="4"><?php echo $astudent['study_centers_name'];?></td>
                        <td rowspan="2">1st Year Courses</td>
                        <?php
                        $counter1=0;
                        foreach ($astudent['course'] as $asc)
                        { 
                            
                            if($asc['learners_session_id']==1){
                                $counter1++;
                            ?>
                        
                        
                        <td rowspan="2" class="subject_name" subject_id='<?php echo $asc['subjects_id']; ?>'><?php echo $asc['subjects_name']; ?>
                        </td>
                        <?php
                            }
                        }
                        for($k=0;$k<(7-$counter1);$k++)
                        {
                            echo '<td rowspan="2">--</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <td><?php echo $astudent['learners_registration_id']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $astudent['learners_name']; ?></td>
                        <td rowspan="2">2nd Year Courses</td>
                        <?php
                        $counter = 0;
                        foreach ($thecourses as $asc2)
                        { 
                            
                            if($asc2['learners_session_id']==2){
                                $counter++;
                            ?>
                        <td rowspan="2" class="subject_name" subject_id='<?php echo $asc2['subjects_id']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $asc2['subjects_name']; ?>
                        </td>
                        
                        <?php
                        }}
                        for($j=0;$j<(7-$counter);$j++)
                        {
                            echo '<td rowspan="2">--</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        
                    </tr>
                       <?php }} ?>
                </tbody>
         </table>      
 </fieldset>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
          <div id='modal_body' class="modal-body">
           
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<?php
include '../body/footer.php';
?>

<script>
    $('.subject_name').on('click',function(){
        //$("#myModal").modal('show');
        //alert();
    var subject_id = $(this).attr('subject_id');
    //alert(subject_id);
        $.ajax({
        //alert(subject_id);
        type : "POST",
        url : 'course_detail_modal.php',
        data : {subject_id : subject_id},
        success : function(data){
            $('modal_body').html(data);
  //$('#subjectmodal').html(data);
            //$("#myModal").modal('show');
           // alert(data);
        }
    });
    });

</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>