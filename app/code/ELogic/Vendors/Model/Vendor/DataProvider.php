<?php

namespace ELogic\Vendors\Model\Vendor;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use ELogic\Vendors\Model\ResourceModel\Vendor\CollectionFactory;
use \Magento\Framework\Filesystem\DirectoryList;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private RequestInterface $request,
        private StoreManagerInterface $storeManager,
        private DirectoryList $directoryList,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $vendorId = (int)$this->request->getParam($this->getRequestFieldName());
        if (empty($vendorId)) {
            return [];
        }
        $vendor = $this->collection->getItemById($vendorId);
        $vendorData = $vendor->getData();
        if (!empty($vendorData['image'])) {
            $vendorData['image'] = [
                $this->getImageInfo($vendorData['image'])
            ];
        }
        $this->loadedData = [
            $vendorId => $vendorData
        ];
        return $this->loadedData;
    }

    /**
     * @param $image
     * @return array
     * @throws FileSystemException
     * @throws NoSuchEntityException
     */
    public function getImageInfo($image)
    {
        $mediaBaseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $image = preg_replace('#^/media/#', '', $image);
        $path = $this->directoryList->getPath('media').'/'.$image;
        $url = rtrim($mediaBaseUrl, '/').'/'.$image;
        $size = 0;
        $mimeType = '';
        if (file_exists($path)) {
            $size = filesize($path);
            $mimeType = mime_content_type($path);
        }
        return             [
            'name' => basename($image),
            'url' => $url,
            'size' => $size,
            'type' => $mimeType,
            'uri' => $image,
        ];
    }
}
