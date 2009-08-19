<?
  
  
  $db_conn = mysql_connect('localhost','root','');
  mysql_select_db('dumalag',$db_conn);
  $count = 0;
  
  $q_patients = mysql_query("SELECT * FROM m_patient ORDER by patient_lastname ASC, patient_firstname ASC") or ("Cannot query: 5");
  
  while($res = mysql_fetch_array($q_patients)){
    $q_fam = mysql_query("SELECT * FROM m_family_members WHERE patient_id='$res[patient_id]'") or die("Cannot query: 11");
    
    if(mysql_num_rows($q_fam)==0):
      $count = $count + 1;
      echo $res[patient_lastname].' ,'.$res[patient_firstname].'<br>';
    endif;
  }
  
  echo "Total: ".$count;




?>