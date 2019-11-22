<?php


namespace PlugRoute\Example;


class D
{
    private $dependecy;

    public function __construct(E $e)
    {
        $this->dependecy = $e;
    }

	public function show()
	{
		echo $this->dependecy->show();
    }
}