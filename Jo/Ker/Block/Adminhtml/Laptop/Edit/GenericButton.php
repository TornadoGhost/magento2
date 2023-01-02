<?php

namespace Jo\Ker\Block\Adminhtml\Laptop\Edit;

use Jo\Ker\Model\LaptopRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

class GenericButton
{
    private UrlInterface $urlBuilder;
    private RequestInterface $request;
    private LaptopRepository $laptopRepository;

    public function __construct(UrlInterface     $urlBuilder,
                                RequestInterface $request,
                                LaptopRepository $laptopRepository)
    {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->laptopRepository = $laptopRepository;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function getProductType()
    {
        $productTypeId = $this->request->getParam('id');
        if ($productTypeId === null) {
            return 0;
        }
        $productType = $this->laptopRepository->getById($productTypeId);

        return $productType->getId() ?: null;
    }

}
