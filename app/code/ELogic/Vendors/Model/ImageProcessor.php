<?php

namespace ELogic\Vendors\Model;

use ELogic\Vendors\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;
use Throwable;

class ImageProcessor
{
    /**
     * @param Filesystem $filesystem
     * @param ImageUploader $imageUploader
     * @param LoggerInterface $_logger
     */
    public function __construct(
        private Filesystem $filesystem,
        private ImageUploader $imageUploader,
        private LoggerInterface $_logger,
    ) {
        //
    }

    /**
     * @param $data
     * @param string $attributeName
     * @return void
     * @throws FileSystemException
     */
    public function process(&$data, string $attributeName = 'image'): void {
        if (empty($data[$attributeName])) {
            $data[$attributeName] = null;
            return;
        }
        $value = $data[$attributeName];
        if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
            try {
                $newImgRelativePath = $this->imageUploader->moveFileFromTmp($imageName, true);
                $value[0]['url'] = $newImgRelativePath;
                $value[0]['name'] = $value[0]['url'];
            } catch (Throwable $e) {
                $this->_logger->critical($e);
            }
        } elseif ($this->fileResidesOutsideCategoryDir($value)) {
            $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
            $value[0]['name'] = $value[0]['url'];
        }
        if ($imageName = $this->getUploadedImageName($value)) {
            if (!$this->fileResidesOutsideCategoryDir($value)) {
                $imageName = $this->checkUniqueImageName($imageName);
            }
            $data[$attributeName] = $imageName;
        } elseif (!is_string($value)) {
            $data[$attributeName] = null;
        }
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable(array $value): bool
    {
        return isset($value[0]['tmp_name']);
    }

    /**
     * Check for file path resides outside of category media dir. The URL will be a path including pub/media if true
     *
     * @param array|null $value
     * @return bool
     */
    private function fileResidesOutsideCategoryDir($value): bool
    {
        if (!is_array($value) || !isset($value[0]['url'])) {
            return false;
        }
        $fileUrl = ltrim($value[0]['url'], '/');
        $baseMediaDir = $this->filesystem->getUri(DirectoryList::MEDIA);
        if (!$baseMediaDir) {
            return false;
        }
        return str_contains($fileUrl, $baseMediaDir);
    }

    /**
     * Gets image name from $value array.
     *
     * Will return empty string in a case when $value is not an array.
     *
     * @param array $value Attribute value
     * @return string
     */
    private function getUploadedImageName(array $value): string
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }
        return '';
    }

    /**
     * Check that image name exists in catalog/category directory and return new image name if it already exists.
     *
     * @param string $imageName
     * @return string
     * @throws FileSystemException
     */
    private function checkUniqueImageName(string $imageName): string
    {
        $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $imageAbsolutePath = $mediaDirectory->getAbsolutePath(
            $this->imageUploader->getBasePath() . DIRECTORY_SEPARATOR . $imageName
        );
        return $this->imageUploader->getBasePath().DIRECTORY_SEPARATOR.call_user_func([Uploader::class, 'getNewFilename'], $imageAbsolutePath);
    }
}
