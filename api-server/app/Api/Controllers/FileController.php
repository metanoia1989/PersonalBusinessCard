<?php

namespace App\Api\Controllers;

use App\Common\Helpers\ResponseHelper;
use App\Api\Forms\FileForm;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Message\Response;

/**
 * Class FileController
 * @package App\Api\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class FileController
{

    /**
     * Upload
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function upload(ServerRequest $request, Response $response)
    {
        // 使用表单验证器
        $form = new FileForm($request->getAttributes(), $request->getUploadedFiles());
        $form->setScenario('upload');
        if (!$form->validate()) {
            $content = ['code' => 1, 'message' => 'FAILED', 'data' => $form->getErrors()];
            return ResponseHelper::json($response, $content);
        }

        // 保存文件
        if ($form->file) {
            $targetPath = app()->basePath . '/runtime/uploads/' . date('Ymd') . '/' . $form->file->getClientFilename();
            $form->file->moveTo($targetPath);
        }

        // 响应
        $content = ['code' => 0, 'message' => 'OK'];
        return ResponseHelper::json($response, $content);
    }

    /**
     * 上传文件到公共目录
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function uploadPublic(ServerRequest $request, Response $response)
    {
        // 使用表单验证器
        $form = new FileForm($request->getAttributes(), $request->getUploadedFiles());
        $form->setScenario('upload');
        if (!$form->validate()) {
            return json_response($response, $form->getErrors(), "参数验证失败", 1);
        }

        // 保存文件
        if ($form->file) {
            $filename = '/uploads/' . date('Ymd') . '/' . $form->file->getClientFilename();
            $targetPath = app()->basePath. '/public' . $filename;
            $form->file->moveTo($targetPath);
            // 响应
            return json_response($response, $filename, "OK", 0);
        } else {
            return json_response($response, null, "无法获取到上传的文件", 1);
        }
    }
}
