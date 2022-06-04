<?php
namespace ELogic\Vendors\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    const NAME = 'image';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $path = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item['image'])) {
                    $item['image'] = preg_replace('#^/media/#', '', $item['image']);
                    $item[$fieldName.'_src'] = $path.$item['image'];
                    $item[$fieldName.'_alt'] = $item['name'];
                    $item[$fieldName.'_orig_src'] = $path.$item['image'];
                }else{
                    $item[$fieldName.'_src'] = $path.'elogic/vendors/placeholder/placeholder.jpg';
                    $item[$fieldName.'_alt'] = 'Place Holder';
                    $item[$fieldName.'_orig_src'] = $path.'elogic/vendors/placeholder/placeholder.jpg';
                }
            }
        }
        return $dataSource;
    }
}
