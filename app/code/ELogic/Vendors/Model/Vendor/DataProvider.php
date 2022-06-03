<?php

namespace ELogic\Vendors\Model\Vendor;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use ELogic\Vendors\Model\ResourceModel\Vendor\CollectionFactory;
use \Magento\Framework\Filesystem\DirectoryList;

class DataProvider extends ModifierPoolDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
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

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $vendorId = (int)$this->request->getParam($this->getRequestFieldName());
        $vendor = $this->collection->getItemById($vendorId);
        $vendorData = $vendor->getData();
        $vendorData['image'] = [
            $this->getImageInfo($vendorData['image'])
        ];
        $this->loadedData = [
            $vendorId => $vendorData
        ];
        return $this->loadedData;
    }

    public function getImageInfo($image): array
    {
        $mediaBaseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $path = $this->directoryList->getPath('media').'/'.$image;
        $url = rtrim($mediaBaseUrl, '/').'/'.$image;
        return             [
            'name' => basename($image),
            'url' => $url,
            'size' => filesize($path),
            'type' => mime_content_type($path),
        ];
    }
}
