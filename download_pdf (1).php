<link rel="stylesheet" href="../assets/bootstrap_modal_4.0/dist/css/bootstrap.min.css">
<script src="../assets/bootstrap_modal_4.0/dist/js/bootstrap.min.js"></script>
<style>
    *{
        font-family:Calibri;
    }
</style>
<?php 
require("phpToPDF.php"); 
include("../inc/config.php");

$batch = $_REQUEST["batch"];

$crm_tracing_training_tracking_sql = mysqli_query($con,"SELECT a.*,b.title_question,b.sub_question FROM `crm_tracing_training_tracking` a JOIN crm_tracing_training b ON a.track_id=b.id WHERE a.batch='$batch' ORDER BY a.id ASC ");

//$pdf=new PDF();
//$pdf->SetFont('Arial','',8);
//$pdf->AddPage();
$wd_month = date("M")."_".date("Y");

$crm_tracing_training_tracking_sql1 = mysqli_query($con,"SELECT sum(percentage) as total_amt FROM `crm_tracing_training_tracking` a  WHERE a.batch='$batch' ORDER BY a.id ASC ");
$tracin_row1=mysqli_fetch_assoc($crm_tracing_training_tracking_sql1);




$total_count=mysqli_num_rows($crm_tracing_training_tracking_sql);
$total_amount = $tracin_row1["total_amt"]/$total_count;
$total_amount_val = number_format((float)$total_amount, 2, '.', '');
$html1='
<HTML>
<div style=\"display:block; padding:10px;font-weight:bold;font-family:Calibri;\">

<div >';
$total_sum=0;$i=1;
while($tracin_row=mysqli_fetch_assoc($crm_tracing_training_tracking_sql)){
    if($i==1){ 
        $user_details_fetch=mysqli_fetch_assoc(mysqli_query($con,"SELECT team FROM `crm_user_details` WHERE username='".$tracin_row["username"]."'  "));
        //echo $total_amount."<br/>";
        if($total_amount<=50)
        {
            $image='<img src="https://intuc.live/vcrm/lib/1.png" style="width:300px;height:auto;" alt="Need immense focus on the areas highlighted by the trainer " />
            <br>
            <span>Your score: '.$total_amount_val.'</span>
            <br/>
            <h6><b>Remarks:</b> Need immense focus on the areas highlighted by the trainer</h6>';
            
        }
        if($total_amount>=50.01 && $total_amount<=60.00)
        {
            $image='<img src="https://intuc.live/vcrm/lib/2.png" style="width:300px;height:auto;" alt="You need focus on the areas highlighted by the trainer to improve the tracing skills " />
            <br/>
            <h6><b>Remarks:</b> You need focus on the areas highlighted by the trainer to improve the tracing skills</h6>';
        }
        if($total_amount>=60.01 && $total_amount<=75.00)
        {
            $image='<img src="https://intuc.live/vcrm/lib/3.png" style="width:300px;height:auto;" alt="You have a good tracing skill but needs improvement " />
            <br>
            <span>Your score: '.$total_amount_val.'</span>
            <br/>
            <h6><b>Remarks:</b> You have a good tracing skill but needs improvement</h6>';
        }
        if($total_amount>=75.01 && $total_amount<=85.00)
        {
            $image='<img src="https://intuc.live/vcrm/lib/4.png" style="width:300px;height:auto;"  alt="You are highly skilled in tracing. There are yet scopes for skill enhancement " />
            <br>
            <span>Your score: '.$total_amount_val.'</span>
            <br/>
            <h6><b>Remarks:</b> You are highly skilled in tracing. There are yet scopes for skill enhancement </h6>';
        }
        if($total_amount>=85.01 && $total_amount<=100.00)
        {
            $image='<img src="https://intuc.live/vcrm/lib/5.png"  style="width:300px;height:auto;" alt="Wow! You are doing a great job.. Keep it up" />
            <br>
            <span>Your score: '.$total_amount_val.'</span>
            <br/>
            <h6><b>Remarks:</b> Wow! You are doing a great job.. Keep it up.</h6>';
        }
        
        
    $html1.='
    <div class="row">
        <table>
            <tr>
                <td>
                    <div class="col-sm-6">
                       '.$image.'
                    </div>
                </td>
                <td>
                    <div class="col-sm-6">
                        <table style="float:right;margin-right:-500px;margin-top: 40px;padding: 45%;" class="">
                            <tr>
                                <th>Date:</th>
                                <td>'.$tracin_row["batch_date"].'</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>'.$tracin_row["username"].'</td>
                            </tr>
                            <tr>
                                <th>Team Leader:</th>
                                <td>'.$user_details_fetch["team"].'</td>
                            </tr>
                            <tr>
                                <th>Trainer:</th>
                                <td>'.$tracin_row["training"].'</td>
                            </tr>
                        </table>
                    </div>
                
                </td>
            </tr>
        
        </table>
            
            
 <br/><hr><br/>       
<div class="col-lg-12">
    <table class="table table-striped">
        <tr>
            <th>SI#</th>
            <th>Titles</th>
            <th>Need Improvement in</th>
        </tr>
    ';
    }
    $html1.='
    <tr>
        <td>'.$i.'</td>
        <td>';
        if(strlen($tracin_row["title_question"])==2)
        {
            $html1.=$tracin_row["sub_question"];
        }
        if(strlen($tracin_row["title_question"])==3)
        {
            $html1.=$tracin_row["sub_question"];
        }
        else {
            if($tracin_row["title_question"]=="10a")
            {
                $html1.="Are they presently using it ?";
            }
            else if($tracin_row["title_question"]=="11a")
            {
                $html1.="Do they enter the keywords in correct order to obtain the desired result?";
            }
            else {
                $html1.=$tracin_row["title_question"];
            }
        }
        $html1.='</td>
        <td><span style="color:red">'.$tracin_row["answer_comment"].'</span></td>';
            if($tracin_row["answer_dropdown"]=="yes")
            {
                $sum=100;
            }
            if($tracin_row["answer_dropdown"]=="py")
            {
                $sum=50;
               
            }
            if($tracin_row["answer_dropdown"]=="no")
            {
                $sum=0;
            }
        $html1.='
    </tr>
    
    ';
    
    if($i==27)
    {
        $html1.='
        <br/><br/>
        <div style="float:right;">
                <table style="margin-left:30%;">
                <tr>
                    <td><strong>Overall Comment:</strong></td>
                    <td>
                    <div style="width:500px;height:100px;border:1px solid black;">
                            '.$tracin_row["comments"].'
                        </div>
                    </td>
                </tr>
                </table>
        </div>';
    }
    $total_sum+=$sum;
    $i++;
}


$html1.='</table>
<br/><br/><br/>
    <hr>
    This is a computer generate report. Hence, require no signature
        </div>
    </div>
</div>
</HTML>';



phptopdf_html("$html1","", "Generate_Tracing_Report_$wd_month.pdf");
echo("<a href='Generate_Tracing_Report_$wd_month.pdf' target='_blank'>Download Your PDF</a>");
echo $html1;


?>


