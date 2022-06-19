<?php

namespace App\Http\Forms;

class InspireSearchForm implements \JsonSerializable
{
    public function __construct(private array $requestParams)
    {
        //
    }

    public function getSpace(): ?string
    {
        return $this->requestParams['space'] ?? null;
    }

    public function getDesignType(): ?string
    {
        return $this->requestParams['designType'] ?? null;
    }

    public function getColor(): ?string
    {
        return $this->requestParams['color'] ?? null;
    }

    public function jsonSerialize()
    {
        return $this->requestParams;
    }
}
