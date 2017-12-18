<?php

namespace Sumup\Api\Http\Exception;
interface RequestExceptionInterface
{
    public function getErrors();
    public function getError();
}
