<?php
error_reporting(E_ERROR | E_PARSE);
//ini_set('precision', 20);
//ini_set('serialize_precision', 25);
function baseu($a,$n){
	$str = substr($a,strpos($a,".")+1);
	$len = strlen($str);
	//precision - power of the new base a = (number of decimals for the initial number +1 ) . Change to greater when you have a base lower than 10 - number of the letter in new number increases 4 in 2 base is 100
	//When you have 1/3 - 3 base conversion take a greater number of decimals than the number of original for 1/3 for example ($len+1)*(number of bits for 9 in that base at least)
	//$i--;
	//array_push($arr_pow,pow($n,$i));
	$str_number = "";
	$i = -1;
	while(strlen($str_number) <= strlen(basei(9,$n))*($len+1)){
		$pow = pow($n,$i);
		$nz1 = strlen(substr($a,strpos($a,".")+1));
		$a = round($a,$nz1);
		$nz2 = strlen(substr($pow,strpos($pow,".")+1));
		$pow = round($pow,$nz2);
		if($a>=$pow){		
			$str_number .= toString(floor($a/$pow));
			$a = fmod($a,$pow);
		}
		else{
			$str_number .= "0";
		}
		$i--;
	}
	return $str_number;
}
function basei($a,$n){
	if($a>=$n){
		$i=1;
		while(pow($n,$i)<=$a){
			if($i==1){
				$arr_pow[0] = pow($n,$i);
			}
			else{
				array_push($arr_pow,pow($n,$i));
			}
			$i++;
		}
		arsort($arr_pow);
		$arr_pow = array_values($arr_pow);
		$str_number = "";

		for($i=0;$i<count($arr_pow);$i++){
			if($i<count($arr_pow)-1){
				if($a>=$arr_pow[$i]){
					$str_number .= toString(floor($a/$arr_pow[$i]));
					$a = fmod($a,$arr_pow[$i]);
				}
				else{
					$str_number .= "0";
				}
			}
			else{
				$str_number .= toString(floor($a/$arr_pow[$i])).toString(fmod($a,$arr_pow[$i]));
				$a=0;
			}
		}
	}
	else{
		$str_number = toString($a);
	}
	return $str_number;
}
function base($a,$n){
	if(strpos($a,".") !== false){
		$cni = substr($a,0,strpos($a,"."));
		$cnu = substr($a,strpos($a,".")+1);
	}
	else{
		$cni = $a;
		$cnu = "";
	}
	if($cni != ""){
		$nri = basei($cni,$n);
	}
	if($cnu != ""){
		$nru = baseu("0.".$cnu,$n);
	}
	$nrf = $nri.($nru?".".$nru:"");
	return strval($nrf);
}
function toString($a){
	if($a>=0 && $a<10){
		$vret = $a;
	}
	else{
		$vret=chr($a+55);
	}
	return (string) $vret;
}
function to10base($nrconv,$basen){
	$v1 = strpos($nrconv,".") === false?(strlen($nrconv)):strpos($nrconv,".");
	$v2 = strrpos($nrconv,".",strpos($nrconv,".")+1) === false?(strlen($nrconv)):strrpos($nrconv,".");
	$zec = 0;
	for($i = $v1+1;$i<($v2-1);$i++){
		$j = $v1-$i;
		if(is_numeric(substr($nrconv,$i,1))){
			$zec += substr($nrconv,$i,1)*pow($basen,$j);
		}
		else{
			$zec += (ord(substr($nrconv,$i,1))-55)*pow($basen,$j);
		}
	}
	$int = 0;
	for($i = 0;$i<$v1;$i++){
		$j = $v1-$i-1;
		if(is_numeric(substr($nrconv,$i,1))){
			$int += substr($nrconv,$i,1)*pow($basen,$j);
		}	
		else{
			$int += (ord(substr($nrconv,$i,1))-55)*pow($basen,$j);
		}
	}
	return $int.($zec?(".".substr($zec,2)):"");
}	
function floor2($a){
	$ret = substr($a,0,strpos($a,".") !== false ?strpos($a,"."):strlen($a));
	return $ret;
}
//echo floor2(0.04/0.04);
function fmod2($a,$b){ 
    $a = floatval($a);
	$b = floatval($b);
	return $a - floor2($a / $b) * $b;
}
function fmod3($a, $b){
	$a = floatval($a);
	$b = floatval($b);
	while ( $b <= $a) {
		$a -= $b;
	}
	return $a;
}
function pow2($a,$n){
	$ret = 1;
	if($n!=0){
		if($n>0){
			while($n>0){
				$ret = $ret*$a;
				$n--;
			}	
		}
		else{
			while($n<0){
				$ret = $ret/$a;
				$n++;
			}
		}	
	}
	else{
		$ret = 1;
	}
	return $ret;
}	
//enter data here
$nr = 16.0625; //the number to be converted
$basen = 8; //the base
//end data
$nrconv = base($nr,$basen);
echo "<br>Original number :".$nr;
echo "<br>Number converted in base ".$basen." is ".$nrconv;
echo "<br>Number converted in base with base convert ".$basen." is ".base_convert(substr($nr,0,strrpos($nr,".")), 10, $basen);
echo "<br>Last 2 decimals before last point are :".substr($nrconv,strrpos($nrconv,".")-2,2); 

echo "<br>Number reconstructed into 10 base :".to10base($nrconv,$basen);
?>