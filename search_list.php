<?php
include_once '../lib/DbManager.php';
include '../body/header.php';
$studyGroupList = rs2array(sql("SELECT 
                                study_groups_id,
                                study_groups_name
                        FROM 
                            study_groups
                        WHERE statuses_id = 1"));
$center_List = rs2array(sql("SELECT 
                                study_centers_id,
                                CONCAT(study_centers_name,'-',study_centers_codes)
                        FROM 
                            study_centers
                        WHERE statuses_id = 1 " . ($search_previlege == 1 ? "" : " AND study_centers_id = '$user_study_centers_id'")));
$batcheList = rs2array(sql("SELECT
                batches_code,
                description
            FROM
                batches
            WHERE statuses_id = 1
            ORDER BY batches_code"));
$report_for_lists = rs2array(sql("SELECT * FROM report_for"));
//print_r($report_for_lists);exit;
?>


<div class="easyui-panel" style="background:#FFF" title="Learners List Summery">
<span class="subject_info" style="display:none;"></span>  
    <form action="" method="POST" id='theForm' class="form" >
    
                <h1>Search Learners Summery List </h1>
                <fieldset class="center_fieldset">
                    <legend>Search Panel</legend>
                    <table>
                        <tr>
                            <th>Study Year <font style="color:#FF0000;font-weight: bolder">*</font></th>
                            <th>:</th>
                            <td>
                                <?php generateYear('yearId',NULL,'required academic_year',2,1);//comboBox('yearId', $yearList, 'NULL', false, 'required academic_year'); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Batch <font style="color:#FF0000;font-weight: bolder">*</font></th>
                            <th>:</th>
                            <td ><?php comboBox('batches_id', $batcheList, 'NULL', TRUE, 'required batch'); ?></td>
                        </tr>
                        <tr>
                            <th>Group</th>
                            <th>:</th>
                            <td><?php comboBox('study_groups_id', $studyGroupList, $selected['study_groups_id'], TRUE) ?></td>
                        </tr>
                        <tr>
                            <th>Force <font style="color:#FF0000;font-weight: bolder">*</font></th>
                            <th>:</th>
                            <td><?php userWiseForceList();?></td>
                        </tr>
                        
                        <tr>
                            <th>Study Center <font style="color:#FF0000;font-weight: bolder">*</font></th>
                            <th>:</th>
                            <td><?php comboBox('study_centers_id', $center_List, $selected['study_centers_id'], TRUE); ?></td>
                        </tr>
                        <tr>
                            <th>Report For</th>
                            <th>:</th>
                            <td>
          
                                <select name="report_for" id="report_for">
                                    <?php
                                    foreach ($report_for_lists as $arepor_for){
                                    ?>
                                    <option value="<?php echo $arepor_for['0']; ?>" data-url="<?php echo $arepor_for[2]; ?>"><?php echo $arepor_for[1]; ?></option>
                                    <?php } ?>
                     
                                </select>
                                
                                
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Registration ID</td>
                            <td>:</td>
                            <td>
                                <input type="text" id="registration_id_from" class = "learners_reg_id" name="registration_id_from" style="width: 39%" value="<?php if(getParam("registration_id_from") != "") echo getParam("registration_id_from");?>">
                                <input type="checkbox" class="between" value="1" id="registration_check" name="between" <?php if(getParam("between") == "1") echo "checked";?>>Between
                                <input type="text" id="registration_id_to" class = "learners_reg_id" name="registration_id_to" style="width: 39%" value="<?php if(getParam("registration_id_to") != "") echo getParam("registration_id_to");?>">
                            </td>
                       </tr>
                        <tr>
                            <td colspan="3">
                                <button id="btn" name="next" value="processNext" class="button">Search</button>
                            </td>
                        </tr>
                    </table>
                </fieldset>
    </form>
<span id="search_result"></span>
</div> 


<script>
$( "#btn" ).on('click',function(event){
  event.preventDefault();
  var search_by_registration = $('#registration_check').is(":checked");
  if($('#registration_id_from').val())
  {
      //alert(($('#registration_id_from').val()));
      var registration_id_from = $('#registration_id_from').val();
      var new_registration_id_from = registration_id_from.replace(/-/g,"");
      if(search_by_registration)
      {
          var registration_id_to = $('#registration_id_to').val();
          var new_registration_id_to = registration_id_to.replace(/-/g,""); 
      }
     
      //var url = $('#report_for :selected').attr('data-url');
    $.ajax({
           type: "GET",
           url: 'learners_list_without_header.php',
           data: {'new_registration_id_from':new_registration_id_from,'new_registration_id_to':new_registration_id_to},
           //dataType: "json",
           success: function(data){
               $('#search_result').html(data);
             //$(".post-votes-count").text("100");
           }
  });
  }
  else{
      var year_id = $("#yearIdID").val();
  var batches_id = $("#batches_idID").val();
  var force_id=3;
  var study_groups_id = $("#study_groups_idID").val();
  var study_centers_id = $("#study_centers_idID").val(); 
  var report_for = $('#report_for :selected').val();
  var url = $('#report_for :selected').attr('data-url');
    $.ajax({
           type: "POST",
           url: url,
           data: {'year_id':year_id,'batches_id':batches_id,'study_groups_id':study_groups_id,'study_centers_id':study_centers_id,'force_id':force_id},
           //dataType: "json",
           success: function(data){
               $('#search_result').html(data);
             //$(".post-votes-count").text("100");
           }
  });
  }
  
});
</script>
<script>
$(document).ready(function (){
      $(".between").change(function (){
          if($(".between").is(":checked"))
          {
              $("#registration_id_from").attr("required","required");
              $("#registration_id_to").attr("required","required");
              $("#registration_id_to").removeAttr("disabled");
              $("#registration_id_to").focus();
          }else
          {
            $("#registration_id_from").removeAttr("required");
            $("#registration_id_to").removeAttr("required");
            $("#registration_id_to").attr("disabled","disabled");
          }
      });
    });
 $('#view_result').click(function(){
     var reg_id = $('#registration_id_from').val();
     $.ajax({
         url:'ajax_generate_result.php',
         data:{registration_id:reg_id},
         success: function(data){
             $('#result_panel').html(data);
         }
     });
 });
 
$('#registration_id_from').on('keyup',function(){
   var form_val = ($(this).val());
   var length = form_val.length;
   if(length<=11){$('#registration_id_to').val(form_val);}
}); 
 
</script>


<?php
include '../body/footer.php';
?>

