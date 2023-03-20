<?php

namespace KH\Api\Controller;

use KH\Entity\File;
use KH\Service\CrudService;
use KH\Service\File\FileService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package KH\Api\Controller
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class FileController extends BaseController
{
    #[Route('/files', methods: ['POST'])]
    public function uploadFiles(Request $request, FileService $fileService)
    {
        $files = $request->files->get('file');

        if (!is_array($files)) {
            $files = [$files];
        }

        $uploaded = [];
        foreach($files as $file) {
            $uploaded[] = $fileService->store($file);
        }

        return $this->success($uploaded, ['groups' => ['file', 'file_detail', 'product']]);
    }

    #[Route('/files/{id}', methods: ['DELETE'])]
    public function removeFile(File $file, CrudService $crudService)
    {
        $crudService->delete($file);
        return $this->success([]);
    }
}