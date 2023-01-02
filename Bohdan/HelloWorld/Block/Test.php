<?php

namespace Bohdan\HelloWorld\Block;

use Magento\Framework\View\Element\Template;

class Test extends \Magento\Framework\View\Element\Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function check(){
        $client = new MongoDB\Client(

        );
    }

    public function test(){
        return 'Its test, bitch';
    }
}
