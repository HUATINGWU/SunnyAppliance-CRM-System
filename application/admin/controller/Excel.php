<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;
use think\Loader;
use PHPExcel_IOFactory;
use PHPExcel;
use think\Request;

class Excel extends  Base
{
    
    public function index()
    {
         return $this->fetch();
    }

    public function outputExcel()
    {
        $path = dirname(__FILE__);
        $production = Db::query("select * from production");
        vendor("PHPExcel.PHPExcel");
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
        $objPHPExcel = new \PHPExcel();
        //设置每列的标题
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '产品ID')
            ->setCellValue('B1', '产品型号')
            ->setCellValue('C1', '产品部门')
            ->setCellValue('D1', '产品图片')
            ->setCellValue('E1', '产品信息')
            ->setCellValue('F1', '产品成本')
            ->setCellValue('G1', '产品售价')
            ->setCellValue('H1', '产品单元')
            ->setCellValue('I1', '产品颜色')
            ->setCellValue('J1', '产品购买方式');
        //存取数据  这边是关键
        foreach ($production as $k => $v) {
            $num = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $v['pro_id'])
                ->setCellValue('B' . $num, $v['pro_name'])
                ->setCellValue('C' . $num, $v['pro_branch'])
                ->setCellValue('D' . $num, $v['pro_image'])
                ->setCellValue('E' . $num, $v['pro_info'])
                ->setCellValue('F' . $num, $v['pro_cost'])
                ->setCellValue('G' . $num, $v['pro_seal'])
                ->setCellValue('H' . $num, $v['pro_unit'])
                ->setCellValue('I' . $num, $v['pro_color'])
                ->setCellValue('J' . $num, $v['pro_buy_way']);
        }
        //设置工作表标题
        $objPHPExcel->getActiveSheet()->setTitle('产品信息表');
        //设置列的宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=产品信息.xlsx");//设置文件标题
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        if($objWriter){
            return $this->success('导出成功');
        }else{
            return $this->error('导出失败');
        }
    }

    public function inputExcel($filepathname){
        $file_path =$filepathname;
        $file_path = iconv('utf-8', 'gbk', $file_path);//使用iconv转换字符集防止文件名为中文时乱码
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        
        $PHPReader = new \PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($file_path)) {
            $PHPReader = new \PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($file_path)) {
                return;
            }
        }
        $objPHPExcel = $PHPReader->load($file_path, $encode = 'utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();//获取总行数
        for ($i = 2; $i <= $highestRow; $i++) {
            $data['pro_name'] = $objPHPExcel->getActiveSheet()->getCell("B" . $i)->getValue();
            $data['pro_branch'] = $objPHPExcel->getActiveSheet()->getCell("C" . $i)->getValue();
            $data['pro_image'] = $objPHPExcel->getActiveSheet()->getCell("D" . $i)->getValue();
            $data['pro_info'] = $objPHPExcel->getActiveSheet()->getCell("E" . $i)->getValue();
            $data['pro_cost'] = $objPHPExcel->getActiveSheet()->getCell("F" . $i)->getValue();
            $data['pro_seal'] = $objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
            $data['pro_unit'] = $objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();
            $data['pro_color'] = $objPHPExcel->getActiveSheet()->getCell("I" . $i)->getValue();
            $data['pro_buy_way'] = $objPHPExcel->getActiveSheet()->getCell("J" . $i)->getValue();
            $allData[] = $data;
        }
        //将文件写入数据库
        foreach ($allData as $value) {
            Db::table('production')->insert($value);
        }


    }
    
    public function uploadFile(){
        // // 获取表单上传文件 例如上传了001.jpg
        // $file = request()->file('excelFile');
        // // 移动到框架应用根目录/public/uploads/ 目录下
        // $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
        // if ($info) {
        //         //将文件写入数据库
        //         $this->uploadExcel($info->getSaveName());
        //         return $this->success('导入成功');
        // } else {
        //     // 上传失败获取错误信息
        //     return $this->error('导入失败');
        // }
        vendor("PHPExcel.PHPExcel"); //方法一
        vendor("PHPExcel.PHPExcel.Writer.Excel5");
        vendor("PHPExcel.PHPExcel.Writer.Excel2007");
        vendor("PHPExcel.PHPExcel.IOFactory");
$objPHPExcel = new \PHPExcel();
//获取表单上传文件
$file = request()->file('excelFile');
$info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
if($info){
//获取文件名
$exclePath = $info->getSaveName(); 
//上传文件的地址
$file_name = ROOT_PATH . 'public' . DS . 'upload' . DS . $exclePath; 
$objReader = \PHPExcel_IOFactory::createReader('Excel2007');
//加载文件内容,编码utf-8
$obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8'); 

echo "<pre>";
$excel_array = $obj_PHPExcel->getsheet(0)->toArray(); //转换为数组格式
array_shift($excel_array); //删除第一个数组(标题);
$data = [];
foreach ($excel_array as $k => $v) {
    $data[$k]['pro_id'] = $v['0'];
$data[$k]['pro_name'] = $v['1'];
$data[$k]['pro_branch'] = $v['2'];
$data[$k]['pro_image'] = $v['3'];
$data[$k]['pro_info'] = $v['4'];
$data[$k]['pro_cost'] = $v['5'];
$data[$k]['pro_seal'] = $v['6'];
$data[$k]['pro_unit'] = $v['7'];
$data[$k]['pro_color'] = $v['8'];
$data[$k]['pro_buy_way'] = $v['9'];
}
//批量插入数据
$success = Db::name('production')->insertAll($data); 
return $this->success('导入成功');
}else{
// 上传失败获取错误信息
return $this->error('导入失败');
}
}

    
    
}