<?php
include_once '../lib/DbManager.php';
//include '../body/header.php';
$registered_subjects_id = $_POST['subject_id'];
//print_r($_POST);exit;
$query = "SELECT
registered_subjects.earned_subjective_marks,
registered_subjects.earned_objective_marks,
registered_subjects.earned_practical_marks,
registered_subjects.earned_total_marks,
registered_subjects.earned_grade,
registered_subjects.exam_attend_type,
registered_subjects.number_of_reexam,
registered_subjects.remarks,
statuses.statuses_name,
learners.learners_registration_id,
learners.learners_name,
learners.learners_id,
learners.exam_year,
subjects.subjects_name,
subjects.subjects_code
FROM
registered_subjects
inner join statuses ON statuses.statuses_id = registered_subjects.exam_status
inner join learners ON learners.learners_id = registered_subjects.learners_id
INNER JOIN subjects ON subjects.subjects_id=registered_subjects.subjects_id
where registered_subjects_id=".$registered_subjects_id;
//echo $query;exit;
//$res = query($query);
//print_r($res);exit;
$row = find($query);
//print_r($row);exit;
//str_split($row->learners_registration_id);
$reg_id = $row->learners_registration_id;
//echo $reg_id;exit;
$row->earned_total_marks = $row->earned_subjective_marks + $row->earned_objective_marks + $row->earned_practical_marks;
$row->learners_registration_id = substr($reg_id, 0,2)."-". substr($reg_id, 2,1)."-". substr($reg_id, 3,2)."-".substr($reg_id, 5,3)."-".substr($reg_id,-3);
?>
  <h4 class="modal-title" id="modal_head"><?php echo "Registration Id: ".$row->learners_registration_id."<br>Student Name :".$row->learners_name."<br>Course Name :".$row->subjects_name."(".$row->subjects_code.")"; ?></h4>
          <div id='modal_body' class="modal-body">
          <table class="table table-active">
                <tbody>
                    <tr>
                        <th align="left">Subject Info</th>
                        <th>Description</th>
                    </tr>
                    <tr>
                        <th style="text-align: left" field="group">Subjective Marks</th>
                        <td><?php echo $row->earned_subjective_marks; ?></td>
                    </tr>
                    <?php
                       if(!empty($row->earned_objective_marks))
                       {
                    ?>
                    <tr>
                        <th style="text-align: left"  field="group">Objective Marks</th>
                         <td><?php echo $row->earned_objective_marks; ?></td>
                    </tr>
                       <?php } ?>
                      <?php
                       if(!empty($row->earned_objective_marks))
                       {
                    ?>
                    <tr>
                        <th style="text-align: left"  field="quantity">Practical Marks</th>
                        <td><?php echo $row->earned_practical_marks; ?></td>
                    </tr>
                     <?php } ?>
                    <tr>
                        <th style="text-align: left" >Grade</th>
                        <td><?php echo $row->earned_grade; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left" >Total Marks</th>
                         <td><?php echo $row->earned_total_marks; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Exam Year</th>
                        <td><?php echo $row->exam_year; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left">Exam Type</th>
                        <td><?php if($row->exam_attend_type==0) {echo "Regular";} else{echo "Irregular(".$row->number_of_reexam.")";}?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left" >Remarks</th>
                        <td><?php echo $row->remarks; ?></td>
                    </tr>
                </tbody>
          </table>
                       </div>
 