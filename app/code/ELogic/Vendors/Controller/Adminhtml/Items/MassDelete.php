<?php
declare(strict_types=1);

namespace ELogic\Vendors\Controller\Adminhtml\Items;

use ELogic\Vendors\Model\ResourceModel\Vendor\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class \ELogic\Vendors\Controller\Adminhtml\Items\MassDelete
 */
class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        Context $context,
        Filter $filter,
        private CollectionFactory $collectionFactory,
        LoggerInterface $logger = null
    ) {
        $this->filter = $filter;
        $this->logger = $logger ?: ObjectManager::getInstance()->create(LoggerInterface::class);
        parent::__construct($context);
    }

    /**
     * Mass Delete Action
     *
     * @return Redirect
     * @throws LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $deleted = 0;
        $deletedError = 0;
        foreach ($collection->getItems() as $vendor) {
            try {
                $vendor->delete();
                $deleted++;
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $deletedError++;
            }
        }
        if ($deleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 vendor(s) have been deleted.', $deleted)
            );
        }
        if ($deletedError) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $deletedError
                )
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('elogic_vendors/items/index');
    }
}
