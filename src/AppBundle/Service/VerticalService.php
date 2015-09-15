<?php
namespace AppBundle\Service;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class VerticalService
{
    const BIKE  = 'bike';
    const PADEL = 'padel';

    protected $request;

    protected $verticalConst = array(self::BIKE, self::PADEL);
    protected $current = null;

    public function __construct(RequestStack $requestStack)
    {
        /** @var Request request */
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getCurrent()
    {
        if (!empty($this->current)) {
            return $this->current;
        }

        if (!empty($this->request)) {
            foreach ($this->verticalConst as $const) {
                if (preg_match("~/" . $const . "[/|?]*~", $this->request->getPathInfo())) {

                    return $const;
                }
            }
        }

        return null;
    }

    public function setCurrent($current)
    {
        $this->current = $current;
    }
}