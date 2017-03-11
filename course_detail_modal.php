<?php
include_once '../lib/DbManager.php';
//include '../body/header.php';
$subject_id = $_POST['subject_id'];
//print_r($_POST);exit;
$query = "SELECT
registered_subjects.earned_subjective_marks,
registered_subjects.earned_objective_marks,
registered_subjects.earned_practical_marks,
registered_subjects.earned_total_marks,
registered_subjects.earned_grade,
registered_subjects.exam_status
FROM
registered_subjects
where registered_subjects_id=".$subject_id;
$row = rs2array(sql($query));
?>

          <table class="common_view">
                <thead>
                    <tr class="fitem">
                        <th field="group">earned_subjective_marks</th>
                        <th field="group">earned_objective_marks</th>
                        <th field="quantity">earned_practical_marks</th>
                        <th>earned_grade</th>
                        <th>earned_total_marks</th>
                        <th>exam_status</th>
                    </tr>
                </thead> 
                <div class="modal-body">
                <tbody>
                    <tr>
                        <td><?php echo $row[0][0]; ?></td>
                        <td><?php echo $row[0][1]; ?></td>
                        <td><?php echo $row[0][2]; ?></td>
                        <td><?php echo $row[0][3]; ?></td>
                        <td><?php echo $row[0][4]; ?></td>
                        <td><?php echo $row[0][5]; ?></td>
                    </tr>
                </tbody>
                