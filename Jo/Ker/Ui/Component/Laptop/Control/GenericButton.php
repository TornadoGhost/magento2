<?php

namespace Jo\Ker\Ui\Component\Laptop\Control;

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
        $laptopId = $this->request->getParam('id');
        if ($laptopId === null) {
            return 0;
        }
        $productType = $this->laptopRepository->getById($laptopId);

        return $productType->getId() ?: null;
    }

}
