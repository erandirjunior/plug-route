<?php

namespace PlugRoute\Container;

class Setting
{
    private int $index;

    private array $data;

    public function __construct()
    {
        $this->index = 1;
        $this->data = [];
    }

    public function addData(string ...$data): void
    {
        $this->data[$this->index][] = $data;
    }

    public function getData(): array
    {
        $data = [];

        foreach ($this->data as $dataList) {
            $data = array_merge($data, ...$dataList);
        }

        return $data;
    }

    public function incrementIndex(): void
    {
        $this->index++;
    }

    public function removeLastPosition(): void
    {
        $this->decrementIndex();
        $this->data = array_slice($this->data, 0, $this->index);
    }

    private function decrementIndex(): void
    {
        if ($this->index > 1) {
            $this->index--;
        }
    }
}