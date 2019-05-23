<?php 

namespace Laraket\Billboard\Contracts;

interface Billboard 
{
    public function lists($start, $end);

    public function add($element, $score);

    public function update($element, $score);

    public function remove($element);

    public function score($element);

    public function rank($element);
}