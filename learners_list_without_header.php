<?php
include_once '../lib/DbManager.php';
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    body #modal-body{
    width:100px;}
  </style>
    <?php
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
        learners.exam_year,
        learners.gpa,
	study_centers.study_centers_name,
        subjects.subjects_name,
	subjects.subjects_code,
	subjects.subjects_id,
	subjects.full_marks,
        registered_subjects.learners_session_id,
        registered_subjects.registered_subjects_id
FROM
	learners
LEFT JOIN registered_subjects ON registered_subjects.learners_id = learners.learners_id
LEFT JOIN subjects ON registered_subjects.subjects_id = subjects.subjects_id
LEFT JOIN study_centers ON learners.current_study_centers_id = study_centers.study_centers_id"
.$where;
//echo $query;exit;
//$items = find($query);
$result = query($query);
$items = array();
while($currentitem = mysql_fetch_assoc($result)) {
    array_push($items, $currentitem);
}
//print_r($items);exit;
if($items)
{
$students = array();
$student = array();
$courses = array();
$is_first_time = 1;
//print_r($items);exit;
foreach ( $items as $row ){
    //print_r($row);exit;
    $course = array();    
    if(!in_array($row['learners_id'], $student)){  //New Student
        
        if($is_first_time != 1){    //Not first time
            $student['course'] = $courses;
            $courses = array();
            $students[] = $student;
            $student = array();
        }
        $is_first_time = 0;
        $student['learners_id'] = $row['learners_id'];
        $student['learners_registration_id'] = $row['learners_registration_id'];
        $student['learners_name'] = $row['learners_name'];
        $student['dateofbirth'] = $row['dateofbirth'];
        $student['study_centers_name'] = $row['study_centers_name'];
        $student['gpa'] = $row['gpa'];
        $student['exam_year'] = $row['exam_year'];
              
    }
    
    if( $row['subjects_name'] != "" ){
        $course['subjects_name'] = $row['subjects_name'];
        $course['subjects_code'] = $row['subjects_code'];
        $course['subjects_id'] = $row['subjects_id'];
        $course['full_marks'] = $row['full_marks'];
        $course['learners_session_id'] = $row['learners_session_id'];
        $course['registered_subjects_id'] =$row['registered_subjects_id'];
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
                        <th>GPA/YEAR</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
//print_r($student);exit;
                       $sl=1;
                       if($students){foreach ($students as $astudent)
                       {
                        $reg_id = $astudent['learners_registration_id'];   
                        $astudent['learners_registration_id']  = substr($reg_id, 0,2)."-". substr($reg_id, 2,1)."-". substr($reg_id, 3,2)."-".substr($reg_id, 5,3)."-".substr($reg_id,-3);   
                        $thecourses = $astudent['course'];   
                        
                    ?>
                    <tr>
                        <td rowspan="4"><?php echo $sl++; ?></td>
                        <td><?php echo $astudent['learners_id']; ?></td>
                        <td rowspan="4"><?php echo $astudent['study_centers_name'];?></td>
                        <td rowspan="2">1st Year Courses</td>
                        <?php
                        $counter1=0;
                        foreach ($astudent['course'] as $asc1)
                        { 
                            //print_r($asc1);exit;
                            if($asc1['learners_session_id']==1){
                                $counter1++;
                                //print_r($asc1);exit;
                            ?>
                        
                        
                        <td class="subject_name" subject_id='<?php echo $asc1['registered_subjects_id']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $asc1['subjects_code']; ?></td>
                        
                        <?php
                            }
                        }
                        for($k=0;$k<(7-$counter1);$k++)
                        {
                            echo '<td>--</td>';
                        }
                        ?>
                        <td rowspan="2"><?php echo $astudent['exam_year']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $astudent['learners_registration_id']; ?></td>
                           <?php
                        $counter2=0;
                        foreach ($astudent['course'] as $asc2)
                        { 
                            
                            if($asc2['learners_session_id']==1){
                                $counter2++;
                            ?>
                        
                        
                        <td class="subject_name" subject_id='<?php echo $asc2['registered_subjects_id']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $asc2['subjects_name']; ?></td>
                        
                        <?php
                            }
                        }
                        for($k=0;$k<(7-$counter2);$k++)
                        {
                            echo '<td rowspan="2">--</td>';
                        }
                        ?>
                    </tr>
                    <tr>
                        <td><?php echo $astudent['learners_name']; ?></td>
                        <td rowspan="2">2nd Year Courses</td>
                        <?php
                        $counter = 0;
                        foreach ($thecourses as $asc3)
                        { 
                            //print_r($thecourses);exit;
                            if($asc3['learners_session_id']==2){
                                $counter++;
                            ?>
                        <td class="subject_name" subject_id='<?php echo $asc3['registered_subjects_id']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $asc3['subjects_code']; ?>
                        </td>
                        
                        <?php
                        }}
                        for($j=0;$j<(7-$counter);$j++)
                        {
                            echo '<td>--</td>';
                        }
                        ?>
                        <td rowspan="2"><?php echo $astudent['gpa']; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $row->grade; ?></td>
                        <?php
                        $counter = 0;
                        foreach ($thecourses as $asc3)
                        { 
                            
                            if($asc3['learners_session_id']==2){
                                $counter++;
                            ?>
                        <td class="subject_name" subject_id='<?php echo $asc3['registered_subjects_id']; ?>' data-toggle="modal" data-target="#myModal"><?php echo $asc3['subjects_name']; ?>
                        </td>
                        
                        <?php
                        }}
                        for($j=0;$j<(7-$counter);$j++)
                        {
                            echo '<td>--</td>';
                        }
                        ?>
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
          <span id="modal_info"></span>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

          </div>
  <script>
    $(document).on('click','.subject_name',function(){
        //$("#myModal").modal('show');
//        alert();
    var subject_id = $(this).attr('subject_id');
    var subject_name = $(this).html();
    //alert(subject_name);
    //$(".modal-title").html(subject_name);
    //alert(subject_id);
        $.ajax({
        //alert(subject_id);
            type : "POST",
            url : 'course_detail_modal.php',
            data : {subject_id : subject_id},
            success : function(data){
            //alert(data);
                $('#modal_info').html(data);
  //$('#subjectmodal').html(data);
                $("#myModal").modal('show');
           // alert(data);
        }
    });
    });

</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


