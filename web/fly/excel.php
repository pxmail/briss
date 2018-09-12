<?php
/**
 * Excel处理函数
 */
namespace fly;
class Excel {
	/**
	 * 表头背景颜色
	 */
	private $_headColor = 'fffccc';
	
	/**
	 * 构造运动项目数据结构
	 */
	public function createMovementStructure() {
		//获取json文件的预定义动作数据
		$json = file_get_contents('config/movements.json');
		$movement = json_decode($json);
		
		//处理预定义动作数据结构
		$data = [];
		foreach ( $movement as $group_name => $group ) {
			foreach ( $group as $movement_name => $m ) {
				if ( !isset($data [$m->id])) {
					$data [$m->id] = [
							'sheetname' => $m->name,
							'fields' => ['training_date']
					];
				}
		
				foreach ( $m->format as $field => $cfg ) {
					$data [$m->id]['fields'][] = $field;
				}
		
			}
		}
		
		return $data;
	}

	/**
	 * 根据运动项目映射相应数据
	 * @param array $trainings
	 * @param array $data
	 */
	public function mapMovementData($trainings, $data) {
		foreach ( $trainings as &$training ) {
			$movement_id = $training ['movement_id'];
			$fields = $data[$movement_id]['fields'];
			if(!isset($data[$movement_id]['data'])) {
				$data[$movement_id]['data'] = [];
			}
				
			$row = [];
			foreach ($fields as &$fieldName) {
				if ($movement_id === '104') {
					switch ($training ['number_4']) {
						case '0' :
							$training ['number_4'] = '无';
							break;
						case '10' :
							$training ['number_4'] = '中等';
							break;
						case '20' :
							$training ['number_4'] = '高';
							break;
					}
				}
				$row[] = $training[$fieldName];
			}
				
			$data[$movement_id]['data'][] = $row;
		}
		
		return $data;
	}
	

	/**
	 * 体能训练监控记录表模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_eval($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,15列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表第二行标题
		$objPHPExcel->getActiveSheet ()->setCellValue ( "A2", "运动员姓名:" . $trainee->name . "    " . "运动项目:" . $trainee->sport );
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:O2' )->getFont ()->setSize ( 16 );
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:O2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "A2:O2" );
	
		// 第三行标题
		$title2 = array (
				'训练日期',
				'训练时间',
				'自我感觉',
				'',
				'',
				'疼痛调查',
				'',
				'RPE',
				'',
				'HRV',
				'',
				'晨脉',
				'',
				'自我训练质量评价',
				'备注'
		);
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A3:O3' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		// 填充第三行标题信息
		for($i = 0; $i < count ( $title2 ); $i ++) {
			$objPHPExcel->getActiveSheet ()->setCellValue ( "$letter[$i]3", "$title2[$i]" );
		}
	
		// 第四行标题
		$tableheader = array (
				'',
				'',
				'训练欲望',
				'睡眠质量',
				'食欲',
				'部位',
				'疼痛等级',
				'训练前',
				'训练后',
				'训练前',
				'训练后',
				'当日',
				'次日',
				'',
				''
		);
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 13 );
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 11 );
		$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'N' )->setWidth ( 20 );
		// 填充第四行标题信息
		for($i = 0; $i < count ( $tableheader ); $i ++) {
			$objPHPExcel->getActiveSheet ()->setCellValue ( "$letter[$i]4", "$tableheader[$i]" );
		}
	
		// 合并第三、四行标题
		$objPHPExcel->getActiveSheet ()->mergeCells ( "A3:A4" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "B3:B4" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "C3:E3" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "F3:G3" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "H3:I3" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "J3:K3" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "L3:M3" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "N3:N4" );
		$objPHPExcel->getActiveSheet ()->mergeCells ( "O3:O4" );
		// 设置第三、四行背景颜色
		$objPHPExcel->getActiveSheet ( 0 )->getStyle ( 'A3:O4' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ( 0 )->getStyle ( 'A3:O4' )->getFill ()->getStartColor ()->setRGB ( self::HEAD_COLOR );
	
		// 设置边框
		$line = count ( $data ) + 4;
		$objPHPExcel->getActiveSheet ()->getStyle ( "A3:O{$line}" )->getBorders ()->getAllBorders ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
	
		// 填充表格信息(评估数据)
		for($i = 5; $i < count ( $data ) + 5; $i ++) {
			$j = 0;
			foreach ( $data [$i - 5] as $key => $value ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( "$letter[$j]$i", "$value" );
				$j ++;
			}
		}
		$flag = $i - 1;
		$objPHPExcel->getActiveSheet ()->getStyle ( "A5:O{$flag}" )->getFont ()->setName ( '黑体' );
		// F（疼痛部位）、G（疼痛等级）列换行
		$objPHPExcel->getActiveSheet ()->getStyle ( "F1:F{$flag}" )->getAlignment ()->setWrapText ( true );
		$objPHPExcel->getActiveSheet ()->getStyle ( "G1:G{$flag}" )->getAlignment ()->setWrapText ( true );
		$objPHPExcel->getActiveSheet ()->getStyle ( "N1:N{$flag}" )->getAlignment ()->setWrapText ( true );
		$objPHPExcel->getActiveSheet ()->getStyle ( "O1:O{$flag}" )->getAlignment ()->setWrapText ( true );
	
		// 表格下方备注部分
		$remarks = array (
				'1、自我感觉调查中训练欲望、睡眠质量、食欲评分原则：1-很差；2-较差；3-中等；4-较好；5-很好。',
				'2、疼痛调查按照国际通用疼痛分级标准填写，疼痛等级为：由轻--重按1--10等级进行填写。',
				'3、RPE：按照国际标准RPE量表进行填写，非常轻松为6，非常累为20。',
				'4、自我训练质量评价填写原则：1-很差；2-较差；3-中等；4-较好；5-很好。'
		);
	
		$start = $i;
		for($k = 0; $k < count ( $remarks ); $k ++) {
			$objPHPExcel->getActiveSheet ()->setCellValue ( "A{$i}", $remarks [$k] );
			$objPHPExcel->getActiveSheet ()->mergeCells ( "A{$i}:O{$i}" );
			$i ++;
		}
		$end = $i - 1;
		$objPHPExcel->getActiveSheet ()->getStyle ( "A{$start}:O{$end}" )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->getActiveSheet ()->getStyle ( "A{$start}:O{$end}" )->getFont ()->setSize ( 10 );
	}
	
	/**
	 * 攀爬机训练(5*30s 高强度间歇性训练)表格模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_101($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,7列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'第一次(英尺)',
				'第二次（英尺）',
				'第三次（英尺）',
				'第四次（英尺）',
				'第五次（英尺）',
				'总高度（英尺）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 15 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	public function temp_102($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,9列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'第一次（英尺）',
				'第二次（英尺）',
				'第三次（英尺）',
				'第四次（英尺）',
				'第五次（英尺）',
				'第六次（英尺）',
				'第七次（英尺）',
				'第八次（英尺）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 15 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	public function temp_103($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,6列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'开始冲刺距离（英尺）',
				'结束冲刺距离（英尺）',
				'冲刺距离（英尺）',
				'总距离（英尺）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 攀爬机训练(耐力训练)表格模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_104($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,5列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'攀爬时间（分钟）',
				'距离（英尺）',
				'总距离（英尺）',
				'阻力'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * Pully训练：转体提拉
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 * @param unknown $trainee
	 */
	public function temp_201($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,3列
		$letter = array (
				'A',
				'B'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'功率（瓦）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 力量耐力测试表格模板(三分钟俯卧撑)
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_301($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,3列
		$letter = array (
				'A',
				'B',
				'C'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'次数（次）',
				'负重（kg）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 力量耐力测试表格模板(三分钟引体向上)
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_302($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,3列
		$letter = array (
				'A',
				'B',
				'C'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'次数（次）',
				'负重（kg）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 力量耐力测试表格模板(俯卧撑最大次数)
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_303($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,3列
		$letter = array (
				'A',
				'B',
				'C'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'次数（次）',
				'负重（kg）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 16 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 力量耐力测试表格模板(引体向上最大次数)
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_304($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,3列
		$letter = array (
				'A',
				'B',
				'C'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'次数（次）',
				'负重（kg）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 16 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 动作速度训练(六边形跳)表格模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_401($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,8列
		$letter = array (
				'A',
				'B',
				'C',
				'D',
				'E',
				'F'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'圈数（圈）',
				'双脚跳用时（秒）',
				'左脚跳用时（秒）',
				'右脚跳用时（秒）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 18 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 动作速度训练(20次双摇)表格模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_402($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,8列
		$letter = array (
				'A',
				'B',
				'C',
				'D'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'时间（秒）',
				'间断次数（次）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 动作速度训练(10秒快速脚步)表格模板
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 */
	public function temp_403($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,4列
		$letter = array (
				'A',
				'B',
				'C'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'次数（次）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 一分钟单摇
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 * @param unknown $trainee
	 */
	public function temp_404($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,4列
		$letter = array (
				'A',
				'B',
				'C',
				'D'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'次数（次）',
				'间断次数（次）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 一分钟双摇
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $data
	 * @param unknown $trainee
	 */
	public function temp_405($objPHPExcel, $sport_event, $data, $trainee) {
		// Excel表格式,4列
		$letter = array (
				'A',
				'B',
				'C',
				'D'
		);
	
		// 设置表格标题及样式
		$this->title ( $objPHPExcel, $letter, $sport_event );
	
		// 表头数组
		$tableheader = array (
				'日期',
				'组编号 第（组）',
				'次数（次）',
				'间断次数（次）'
		);
		// 设置表头名称及样式
		$this->header ( $objPHPExcel, $letter, $tableheader );
	
		// 设置默认列宽
		$objPHPExcel->getActiveSheet ()->getDefaultColumnDimension ()->setWidth ( 20 );
	
		// 填充表格数据
		$this->fill_table ( $objPHPExcel, $letter, $data );
	}
	
	/**
	 * 设置表格标题操作
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $letter
	 * @param unknown $title
	 */
	public function title($objPHPExcel, $letter, $title) {
		// 设置默认字号大小
		$objPHPExcel->getActiveSheet ()->getDefaultStyle ()->getFont ()->setSize ( 11 );
		// 设置默认字体
		$objPHPExcel->getActiveSheet ()->getDefaultStyle ()->getFont ()->setName ( '宋体' );
		// 设置默认水平垂直居中
		$objPHPExcel->getActiveSheet ()->getDefaultStyle ()->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		// 设置默认水平居中
		$objPHPExcel->getActiveSheet ()->getDefaultStyle ()->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		// 表上方标题
		$objPHPExcel->getActiveSheet ()->setCellValue ( "A1", $title );
		// 设置标题加粗
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A1' )->getFont ()->setBold ( true );
		// 设置标题垂直居中
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
		// 设置标题水平居中
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		// 设置标题字号
		$objPHPExcel->getActiveSheet ()->getStyle ( 'A1' )->getFont ()->setSize ( 20 );
		// 合并标题单元格
		$column_count = count ( $letter ); // 列数
		$flag = $column_count - 1; // 最后一列字母的下标
		$objPHPExcel->getActiveSheet ()->mergeCells ( "A1:$letter[$flag]1" );
	}
	
	/**
	 * 设置表头内容及样式操作
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $letter
	 * @param unknown $tableheader
	 */
	public function header($objPHPExcel, $letter, $tableheader) {
		// 填充表头信息
		$column_count = count ( $letter );
		for($i = 0; $i < $column_count; $i ++) {
			$objPHPExcel->getActiveSheet ()->setCellValue ( "$letter[$i]2", "$tableheader[$i]" );
		}
		// 设置表头背景颜色
		$flag = $column_count - 1;
		$objPHPExcel->getActiveSheet ()->getStyle ( "A2:$letter[$flag]2" )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
		$objPHPExcel->getActiveSheet ()->getStyle ( "A2:$letter[$flag]2" )->getFill ()->getStartColor ()->setRGB ( self::HEAD_COLOR );
	}
	
	/**
	 * 填充表格数据操作
	 *
	 * @param unknown $objPHPExcel
	 * @param unknown $letter
	 * @param unknown $data
	 */
	public function fill_table($objPHPExcel, $letter, $data) {
		for($i = 3; $i < count ( $data ) + 3; $i ++) {
			$j = 0;
			foreach ( $data [$i - 3] as $key => $value ) {
				$objPHPExcel->getActiveSheet ()->setCellValue ( "$letter[$j]$i", "$value" );
				$j ++;
			}
		}
	
		// 设置边框
		$column_count = count ( $letter ); // 列数
		$line = count ( $data ) + 2; // 行数
		$flag = $column_count - 1; // 最后一列字母的下标
		$objPHPExcel->getActiveSheet ()->getStyle ( "A2:$letter[$flag]{$line}" )->getBorders ()->getAllBorders ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
	}
	
}