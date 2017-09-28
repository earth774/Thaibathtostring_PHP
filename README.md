# ThaiBath_PHP
PHP แปลงค่าเงินจากตัวเลขเป็นตัวหนังสือ
## การใช้งาน 
### Function ส่วน check เลข
  โดยเมื่อถ้าเรากรอกข้อมูล 45๓๔๖ ก็จะทำการส่งค่า 1 ออกไป เพื่อบอกว่าเป็นเลขไทย
  โดยจะตรวจสอบจากจำนวนถ้าเกิดมี เลขอาราบิกมากกว่าก็จะกลายเป็น อาราบิก
  ```php
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
  ```
### Function ส่วน เปลียนแปลงให้เป็นข้อความ
  ```php
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
  ```
  // thaibath = ห้าร้อยสี่สิบหกบาทถ้วน
  
### Function ซับหน่วย
  จะทำการซับหน่อย เช่น 10 = สิบ  10,000 = หมื่น 
```php
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
  ```
  
### Function PHP Split
  โดย จะทำให้ string ทั้งหมด ถูกเก็บเป็นรูปแบบ array 
  เช่น 5000 = Array([0]=>5,[1]=>0,[2]=>0,[3]=>0);
  ใครอาจ งง ทำไม ไม่ใช้ str_split เลยอะ 
  เพราะ str_split ธรรมดามันได้สามารถอ่านตัวอักษรไทยได้นั้นเอง 
  เลยต้องทำการแปลงเป็น UTF-8 ก่อนนั้นเอง
```php
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
  ```
### Function หาความยาวตัวหนังสือ
  ทำการนับ string เพื่อหาจำนวนว่า string นั้นมีจำนสนเท่าไหร่
   เจจาวา (6)
```php
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
  ```
  ### ส่วนแสดงผล
  ทำการรับข้อมูลจาก form เพื่อมาคำนวน จากนั้น จะ เห็นว่า str_replace เพื่อที่จะดักไว้เพื่อผู้ใช้ กรอก ลูกน้ำ (,)
  โปรแกรมจะได้ไม่ error แล้วก็นำตัวเลขไปแปลง ให้กายเป็น string ดังที่ต้องการได้เลย :)
```php
  $num = @$_POST['number']; 
$num= str_replace(",","", $num);
if ($num=="") {
	?>
	<p style="color: Green;">ไม่มีข้อมูล</p>
	<?php
}else{
	echo  $num  . "&nbsp;=&nbsp;" .ThaiNumberToText($num),"<br>"; 
}
  ```
  
  
FB:ศรีหมอก 
