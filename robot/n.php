<?php
class _oopf_n{
	public $Path = 'robot/n/';//位元的储存路径
	public $Feel = 0;
	public $output_array = array();//信号输出缓冲
	public $SpacePointer_array = array('x' => 0,'y' => 0,'z' => 0);//镜像空间指针
	public $SpaceSize_array = array('x' => 10,'y' => 10,'z' => 10);//镜像空间尺寸
	
	public $space_array_array_array = array();//镜像
	
	public function ReadObjectCode($temp_array,$z_array_max,$y_array_max,$x_array_max){//一维转三维
		$x_count = 0;
		$y_count = 0;
		$z_count = 0;
		
		$temp_array_count = 0;
		while(isset($temp_array[$temp_array_count])){
		///////////////////////////////////////////////////////////////
			$z_array[$z_count][$y_count][$x_count] = $temp_array[$temp_array_count];
			++$x_count;
			if($x_count == $x_array_max){
				$x_count = 0;
				++$y_count;
				if($y_count == $y_array_max){
					$y_count = 0;
					++$z_count;
					if($z_count == $z_array_max){
						break;
					}
				}
			}
		///////////////////////////////////////////////////////////////
			++$temp_array_count;
		}
		return $z_array;
	}
	
	public function WriteObjectCode($Matrix_array){//三维转一维
		$temp_array = array();
		
		$x_count = 0;
		$y_count = 0;
		$z_count = 0;
		
		while(isset($Matrix_array[$z_count])){
			while(isset($Matrix_array[$z_count][$y_count])){
				while(isset($Matrix_array[$z_count][$y_count][$x_count])){
					array_push($temp_array,$Matrix_array[$z_count][$y_count][$x_count]);
					++$x_count;
				}
				$x_count = 0;
				++$y_count;
			}
			$y_count = 0;
			++$z_count;
		}
		
		return $temp_array;
	}
	
	public function BuildSpace(){//创建镜像
		$temp_array = array();
		$temp_count_max = $this->SpaceSize_array['z']*$this->SpaceSize_array['y']*$this->SpaceSize_array['x'];
		$temp_count = 0;
		while($temp_count != $temp_count_max){
			array_push($temp_array,0);
			++$temp_count;
		}
		return $temp_array;
	}
	
	public function ReadSpaceFile(){//读取镜像空间
		if(!file_exists($this->Path.'space.txt')){
			$temp_array = $this->BuildSpace();
			file_put_contents($this->Path.'space.txt',implode('',$temp_array));
		}
		else{
			$temp_array = cut_word(file_get_contents($this->Path.'space.txt'));
		}
		$this->space_array_array_array = $this->ReadObjectCode($temp_array,$this->SpaceSize_array['z'],$this->SpaceSize_array['y'],$this->SpaceSize_array['x']);//将一维转化为三维数组
	}
	
	public function WriteSpaceFile(){//写入镜像空间
		$temp_array = $this->WriteObjectCode($this->space_array_array_array);
		file_put_contents($this->Path.'space.txt',implode('',$temp_array));
	}
	
	public function GetObjectToSpace($xyz_array,$Object_array_array_array){//把对象放进镜像中
		
	}
}
?>