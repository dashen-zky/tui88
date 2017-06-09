<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 17-4-26
 * Time: 下午5:25
 */

namespace common\models;

use Yii;
use yii\web\Response;
use common\helpers\ExternalFileHelper;
use common\helpers\PlatformHelper;


class FileUploadAndDelete
{

    public function fileUpload($request,$response)
    {
        $targetUploadDirectory = ExternalFileHelper::getOtherAbsoluteDirectory();
        // 文件夹不存在，则创建
        if (!is_dir($targetUploadDirectory)) {
            mkdir(iconv('UTF-8', 'GBK', $targetUploadDirectory), 0777, true);
        }
        if ($request->isPost) {
            $response->format = Response::FORMAT_JSON;
            $fileExt = substr(strrchr($_FILES['file']['name'], '.'), 1);
            $newFileName = PlatformHelper::getUUID() . '.' . $fileExt;
            $newFileFullPath = $targetUploadDirectory . $newFileName;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $newFileFullPath)) {
                return ['err_code' => 0, 'msg' => '上传成功', 'file_name' => $newFileName];
            }
            return ['err_code' => 1, 'msg' => '上传失败'];
        }
    }

    /**
     * 物理删除图片
     * @return array
     */
    public function fileDelete($request)
    {
        if ($request->isGet) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $imgName = $request->get('img_name');

            $targetUploadDirectory = ExternalFileHelper::getOtherAbsoluteDirectory();

            $fileFullPath = $targetUploadDirectory . $imgName;
            if (file_exists($fileFullPath)) {
                if (unlink($fileFullPath)) {
                    return ['err_code' => 0, 'err_msg' => '删除成功'];
                } else {
                    return ['err_code' => 1, 'err_msg' => '删除失败'];
                }
            } else {
                return ['err_code' => 2, 'err_msg' => '文件不存在'];
            }
        }
    }

}