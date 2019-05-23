<?php 

namespace Laraket\Billboard;

use Laraket\Billboard\Contracts\Billboard as BillboardContract;
use Illuminate\Support\Facades\Redis;

abstract class Billboard implements BillboardContract 
{

    /**
     * Get the rank name
     */
    abstract function getRankName();

    /**
     * Show a rank list in (start, end)
     * 
     * @param int $start start index
     * @param int $end   end index
     * 
     * @return array
     */
    public function lists($start, $end)
    {
        return Redis::zrevrange($this->getRankName(), $start, $end, 'WITHSCORES');
    }

    /**
     * Add a element
     * 
     * @param mixed $element element
     * 
     * @return void
     */
    public function add($element)
    {
        Redis::zadd($this->getRankName(), $score, $element);
    }

    /**
     * Add score for one element
     * 
     * @param mixed $element element
     * @param int   $score   score
     * 
     * @return void
     */
    public function increment($element, $score)
    {
        Redis::zincrby($this->getRankName(), $score, $element);
    }

    /**
     * Remove a element
     * 
     * @param mixed $element element
     * 
     * @return void
     */
    public function remove($element)
    {
        Redis::zrem($this->getRankName(), $element);
    }

    /**
     * Get one element's score
     * 
     * @param mixed $element element
     * 
     * @return float|int
     */
    public function score($element)
    {
        return Redis::zscore($this->getRankName(), $element) ?: 0;
    }
    
    /**
     * Get rank index base on score
     * 
     * @param mixed $element element
     * 
     * @return int
     */
    public function rank($element): int
    {
        $rank = Redis::zrevrank($this->getRankName(), $element);
        
        if ($rank === false) {
            $this->add(0, $element);
        }

        return Redis::zrevrank($this->getRankName(), $element) + 1;
    }

}