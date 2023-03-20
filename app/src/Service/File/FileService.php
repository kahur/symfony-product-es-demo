<?php

namespace KH\Service\File;


use Doctrine\Persistence\ManagerRegistry;
use KH\Entity\File;
use KH\Service\CrudService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @package KH\Service\File
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class FileService extends CrudService
{
    public function __construct(
        protected string $fileDir,
        ManagerRegistry $managerRegistry
    ) {

        parent::__construct($managerRegistry);
    }

    public function store(UploadedFile $file):? File
    {
        $customName = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $entity = new File();
        $entity->setName($file->getClientOriginalName());
        $entity->setType($file->getClientMimeType());
        $entity->setMetaData((array) $file->getFileInfo());
        $entity->setPath(realpath($this->fileDir) . '/' . $customName);

        if ($file->move($this->fileDir, $customName)) {
            return $this->save($entity);
        }

        return false;
    }
}