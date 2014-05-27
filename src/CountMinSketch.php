<?php

class CountMinSketch
{
    protected $depth;

    protected $width;

    protected $hashTable;

    public function __construct($HLL_P = 14, $confidence = 0.0001)
    {
        $this->depth = ceil(log(1.0 / $confidence));

        $this->width = 1 << $HLL_P;

        $this->hashTable = array();

        for ($i = 0; $i < $this->depth; $i++) {
            $this->hashTable[$i] = array();
            for ($k = 0; $k < $this->width; $k++) {
                $this->hashTable[$i][$k] = 0;
            }
        }
    }

    protected function hash($key, $seed)
    {
        return crc32(md5($key . $seed)) % $this->width;
    }

    private function updateQueryInternal($item, $count = 0, $query = true)
    {
        $min = INF;

        for($i = 0; $i < $this->depth; $i++) {

            $hash = $this->hash($item, $i);

            $this->hashTable[$i][$hash] += $count;

            $query and $min = min($min, $this->hashTable[$i][$hash]);
        }

        return $query ? $min : null;
    }

    public function query($item)
    {
        return $this->updateQueryInternal($item);
    }

    public function update($item, $count = 1)
    {
        $this->updateQueryInternal($item, $count, false);
    }

    public function updateQuery($item, $count = 1)
    {
        return $this->updateQueryInternal($item, $count);
    }

    public function export()
    {
        return gzcompress(json_encode($this->hashTable));
    }

    public function import($str)
    {
        $this->hashTable = json_decode(gzuncompress($str));
    }
}

