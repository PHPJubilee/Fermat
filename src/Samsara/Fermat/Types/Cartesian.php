<?php

namespace Samsara\Fermat\Types;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\TrigonometryProvider;
use Samsara\Fermat\Values\ImmutableNumber;

class Cartesian
{

    /**
     * @var Tuple
     */
    private $identity;

    /**
     * @var ImmutableNumber
     */
    private $dimensions;

    public function __construct(...$dimensions)
    {
        $this->dimensions = Numbers::make(Numbers::IMMUTABLE, count($dimensions));

        $this->identity = new Tuple($dimensions);
    }

    public function getDimensions()
    {
        return $this->dimensions;
    }

    public function getAxis($axis)
    {
        return $this->identity->get($axis);
    }

    public function getAllAxes()
    {
        return $this->identity->all();
    }

    public function distanceFrom(Cartesian $cartesian, $returnType = Numbers::IMMUTABLE)
    {
        return TrigonometryProvider::cartesianDistance($this, $cartesian, $returnType);
    }

    public function performPairedOperation(Cartesian $cartesian, callable $function)
    {
        if (!$this->ofSameDimensionalityAs($cartesian)) {
            throw new \InvalidArgumentException('Cannot operate on two cartesians of different dimensionality.');
        }

        for ($i = 0; $i < $this->dimensions->getValue(); $i++) {
            yield $function($this->getAxis($i), $cartesian->getAxis($i));
        }
    }

    public function performOperation(callable $function)
    {
        for ($i = 0; $i < $this->dimensions->getValue(); $i++) {
            yield $function($this->getAxis($i));
        }
    }

    public function ofSameDimensionalityAs(Cartesian $cartesian)
    {
        return ($this->getDimensions()->getValue() == $cartesian->getDimensions()->getValue());
    }
}
