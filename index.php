<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>	
	<div >
	<form action="#" method="POST" style="margin: auto;
    width: 30%;
    border: 3px solid green;
    padding: 10px;">
		<input type="text" name="number" placeholder="กรุณากรอกจำนวนเงิน" ><br>
		<input type="submit" value="Sent">
	</form>
	</div>
</body>
</html>
<?php
	function ThaiNumberToText($Number){
		if(!ck_number($Number)){
			if(intval($Number)!=0){
			$Number = str_replace (array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),$Number);  
				$Number *=35;
			}
		}
		$Number = str_replace (array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),$Number);  
		// print_r($a);
		?>
				<script>
					console.log(<?=$arr1?>);
				</script>
				<?php
		if($Number!=0){
			if(is_int(intval($Number))){
				return 	Convert($Number);
			}
		}else{
				?>
				<script >
					alert('Not');
				</script>
				<?php
			}
		
	}
	// check number 123๔๕ to 12345 
	function ck_number($Number){
		$att1 = getMBStrSplit($Number);
		$a = 0;
		$thai=0;
		$alar = 0;
		for ($i=0; $i <getStrLenTH($Number) ; $i++) { 
			$a = 0;
			for ($j=0; $j <=9 ; $j++) { 
				$s= array( "o" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" );
				if($att1[$i]==$s[$j]){
					$thai+=1;
					$a=1;
				}else if($att1[$i]=='.'){
					$a=1;
				}
			}
			if ($a==0) {
				$alar+=1;
			}
		}
		if($thai>=$alar){
			$b=1;
		}else{
			$b=0;
		}
		return $b;
	}

	function Convert($amount_number)
{
    @$amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{

    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    @$number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
// Convert a string to an array with multibyte string
function getMBStrSplit($string, $split_length = 1){
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8'); 
	
	$split_length = ($split_length <= 0) ? 1 : $split_length;
	$mb_strlen = mb_strlen($string, 'utf-8');
	$array = array();
	$i = 0; 
	
	while($i < $mb_strlen)
	{
		$array[] = mb_substr($string, $i, $split_length);
		$i = $i+$split_length;
	}
	
	return $array;
}
// Get string length for Character Thai
function getStrLenTH($string)
{
	$array = getMBStrSplit($string);
	$count = 0;
	
	foreach($array as $value)
	{
		$ascii = ord(iconv("UTF-8", "TIS-620", $value ));
		
		if( !( $ascii == 209 ||  ($ascii >= 212 && $ascii <= 218 ) || ($ascii >= 231 && $ascii <= 238 )) )
		{
			$count += 1;
		}
	}
	return $count;
}
## วิธีใช้งาน
$num = @$_POST['number']; 
$num= str_replace(",","", $num);
if ($num=="") {
	?>
	<p style="color: Green;">ไม่มีข้อมูล</p>
	<?php
}else{
	echo  $num  . "&nbsp;=&nbsp;" .ThaiNumberToText($num),"<br>"; 
}


?>

