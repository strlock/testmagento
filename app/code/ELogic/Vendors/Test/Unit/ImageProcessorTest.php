<?php
declare(strict_types=1);

namespace ELogic\Vendors\Test\Unit;

use ELogic\Vendors\Model\ImageProcessor;
use ELogic\Vendors\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ImageProcessorTest extends TestCase
{
    private ObjectManager $objectManager;
    private object $imageProcessor;
    private string $rootPath;

    protected function setUp(): void
    {
        $this->objectManager = new ObjectManager($this);
        $this->rootPath = realpath(__DIR__.'/../../../../../..');
        $directoryList = $this->objectManager->getObject(DirectoryList::class, [
            'root' => $this->rootPath,
            'config' => [
                'base' => ['path' => $this->rootPath],
            ],
        ]);
        $readFactory = $this->objectManager->getObject(ReadFactory::class);
        $writeFactory = $this->objectManager->getObject(WriteFactory::class);
        $filesystem = $this->objectManager->getObject(Filesystem::class, [
            'directoryList' => $directoryList,
            'readFactory' => $readFactory,
            'writeFactory' => $writeFactory,
        ]);
        $imageUploader = $this->objectManager->getObject(ImageUploader::class);
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->imageProcessor = $this->objectManager->getObject(ImageProcessor::class, [
            'filesystem' => $filesystem,
            'imageUploader' => $imageUploader,
            '_logger' => $logger,
        ]);
    }

    public function testProcess()
    {
        $data = [
            'entity_id' => "123",
            'name' => "TEST",
            'description' => "TEST",
            'image' => [
                [
                    'name' => "test.jpeg",
                    'url' => "http://magento1/media/elogic/vendors/test/test.jpeg",
                    'size' => "7437",
                    'type' => "image/jpeg",
                    'uri' => "elogic/vendors/test/test.jpeg",
                    'previewType' => "image",
                    'id' => "aW5kZXgxXzFfNC5qcGVn",
                ]
            ],
            'created_at' => "2022-06-04 14:19:57",
            'form_key' => "ga1EoHlSYggom4vF",
        ];
        $this->imageProcessor->process($data);
        $this->assertFileExists($this->rootPath.'/pub/'.$data['image']);
    }
}
